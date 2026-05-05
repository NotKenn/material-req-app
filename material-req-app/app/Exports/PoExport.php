<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class PoExport
{
    protected $data;
    protected $lastRow = 20;

    public function __construct($poId)
    {
        $this->data = $this->prepare($poId);
    }

    private function prepare($poId)
    {
        $po = \App\Models\PoDetails::findOrFail($poId);
        $vendor = \App\Models\Vendor::find($po->vendorID);

        $getPenerimaID = DB::table('mr_table')
                        ->join('po_mr', 'mr_table.id', '=', 'po_mr.mr_id')
                        ->where('po_mr.po_id', $po->id)
                        ->pluck('mr_table.penerima_id')
                        ->first();

        $penerima = \App\Models\penerima::where('id', $getPenerimaID)->first();

        $mrCodes = DB::table('mr_table')
            ->join('po_mr', 'mr_table.id', '=', 'po_mr.mr_id')
            ->where('po_mr.po_id', $poId)
            ->pluck('mr_table.kodeRequest')
            ->toArray();

        $items = \App\Models\PoItems::where('po_id', $poId)->get();

        $approvals = \App\Models\approvals::where('approvable_id', $po->id)
                        ->where('approvable_type', \App\Models\PoDetails::class)
                        ->latest('approved_at')
                        ->first();

        $subtotal = 0;
        $totalItemDiscount = 0;

        foreach ($items as $item) {
            $itemSubtotal = $item->price * $item->qty;

            $discountRaw = trim((string) $item->discount);
            $discountValue = 0;

            if ($discountRaw !== '') {
                if (str_contains($discountRaw, '%')) {
                    $percent = (float) str_replace('%', '', $discountRaw);
                    $discountValue = ($percent / 100) * $itemSubtotal;
                } else {
                    $discountValue = (float) preg_replace('/[^0-9.]/', '', $discountRaw);
                }
            }

            $item->calculated_discount = $discountValue;
            $item->final_total = $itemSubtotal - $discountValue;

            $subtotal += $itemSubtotal;
            $totalItemDiscount += $discountValue;
        }

        $globalDiscRaw = $po->gl_disc;
        $globalDiscValue = 0;

        if ($globalDiscRaw) {
            if (str_contains($globalDiscRaw, '%')) {
                $percent = (float) str_replace('%', '', $globalDiscRaw);
                $globalDiscValue = ($percent / 100) * ($subtotal - $totalItemDiscount);
            } else {
                $globalDiscValue = (float) preg_replace('/[^0-9.]/', '', $globalDiscRaw);
            }
        }

        $totalDiscount = $totalItemDiscount + $globalDiscValue;
        $grandTotal = max($subtotal - $totalDiscount, 0);

        $creator = \App\Models\User::find($po->user_id);
        $supervisor = DB::table('users')->where('id', $approvals?->user_id)->first();

        $signaturePath = $creator?->signature
        ? storage_path('app/public/' . $creator->signature)
        : null;
        $supervisorSignature = $supervisor?->signature
            ? storage_path('app/public/'.$supervisor?->signature)
            : null;
        $signName = $creator?->name;
        $superSignName = $supervisor->name;


        return [
            'po' => $po,
            'vendor' => $vendor,
            'penerima' => $penerima,
            'mr_codes' => implode(', ', $mrCodes),
            'items' => $items,
            'totals' => [
                'subtotal' => $subtotal,
                'discount' => $totalDiscount,
                'grand' => $grandTotal
            ],
            'signature' => $signaturePath,
            'supersign' => $supervisorSignature,
            'signname' => $signName,
            'superSignName' => $superSignName
        ];
    }

    public function download()
    {
        $spreadsheet = IOFactory::load(storage_path('app/private/templates/po_templates.xlsx'));
        $sheet = $spreadsheet->getActiveSheet();

        $this->injectHeader($sheet);
        $this->injectItems($sheet);
        $this->injectTotals($sheet);
        $this->injectSignature($sheet);
        $this->injectSignName($sheet);
        $this->injectVendor($sheet);
        $this->injectReceiver($sheet);

        return response()->streamDownload(function () use ($spreadsheet) {
            IOFactory::createWriter($spreadsheet, 'Xlsx')->save('php://output');
        }, 'po.xlsx');
    }

    private function injectHeader($sheet)
    {
        $po = $this->data['po'];
        $vendor = $this->data['vendor'];

        $sheet->setCellValue('F6', $po->companyName);
        $sheet->setCellValue('F7', $po->officeAddress);
        $sheet->setCellValue('F8', $po->contactName);
        $sheet->setCellValue('F9', $po->phone);

        $sheet->setCellValue('K6', $po->po_number);
        $sheet->setCellValue(
            'K7',
            \Carbon\Carbon::parse($po->date)->format('d-m-Y')
        );
        $sheet->setCellValue('K8', $this->data['mr_codes']);
        $sheet->setCellValue('K9', $po->termOfPayment);

        $sheet->getStyle('F7')->getAlignment()->setWrapText(true);
    }

    // 🔥 CORE FIX (ANTI RUSAK TEMPLATE)
    private function injectItems($sheet)
    {
        $items = $this->data['items'];

        $startRow = 18; // template row
        $currentRow = $startRow;
        $no = 1;

        foreach ($items as $index => $item) {

            if ($index > 0) {
                $this->duplicateRow($sheet, $startRow, $currentRow);
            }
            $sheet->setCellValue("B{$currentRow}", $no);
            $sheet->setCellValue("C{$currentRow}", $item->itemName);
            $sheet->setCellValue("H{$currentRow}", $item->qty);
            $sheet->setCellValue("I{$currentRow}", $item->unit);
            $sheet->setCellValue("K{$currentRow}", $item->price);
            $sheet->setCellValue("L{$currentRow}", $item->amount);

            $currentRow++;
            $no++;
        }

        $this->lastRow = $currentRow - 1;
    }

    // 🔥 DUPLICATE ROW DENGAN STYLE + MERGE
    private function duplicateRow($sheet, $sourceRow, $targetRow)
    {
        $sheet->insertNewRowBefore($targetRow, 1);

        foreach (range('A', 'L') as $col) {
            $sheet->duplicateStyle(
                $sheet->getStyle("{$col}{$sourceRow}"),
                "{$col}{$targetRow}"
            );
        }

        // copy row height
        $sheet->getRowDimension($targetRow)->setRowHeight(
            $sheet->getRowDimension($sourceRow)->getRowHeight()
        );

        // copy merge
        foreach ($sheet->getMergeCells() as $merge) {
            if (preg_match("/([A-Z]+){$sourceRow}:([A-Z]+){$sourceRow}/", $merge)) {
                $sheet->mergeCells(str_replace($sourceRow, $targetRow, $merge));
            }
        }
    }

    // 🔥 TOTALS (AMAN, TIDAK GESER TEMPLATE)
    private function injectTotals($sheet)
    {
        $items = $this->data['items'];

        $subtotal = 0;
        $grandTotal = 0;

        foreach ($items as $item) {
            $itemSubtotal = $item->qty * $item->price;

            $subtotal += $itemSubtotal;
            $grandTotal += $item->final_total;
        }

        $discount = $subtotal - $grandTotal;

        // 🔥 posisi ikut row terakhir item
        $row = $this->lastRow + 5;

        $sheet->setCellValue("L{$row}", $subtotal);
        $sheet->setCellValue("L" . ($row + 1), $discount);
        $sheet->setCellValue("L" . ($row + 2), $grandTotal);
    }
private function injectSignature($sheet)
{
    $row = $this->lastRow + 10;

    // 🔥 CREATOR SIGN
    if (!empty($this->data['signature']) && file_exists($this->data['signature'])) {
        $drawing1 = new Drawing();
        $drawing1->setPath($this->data['signature']);
        $drawing1->setHeight(60);
        $drawing1->setCoordinates("K{$row}");

        // 🔥 center-ish positioning
        $drawing1->setOffsetX(40); // adjust kalau kurang pas
        $drawing1->setOffsetY(10);

        $drawing1->setWorksheet($sheet);
    }

    // 🔥 SUPERVISOR SIGN
    if (!empty($this->data['supersign']) && file_exists($this->data['supersign'])) {
        $drawing2 = new Drawing();
        $drawing2->setPath($this->data['supersign']);
        $drawing2->setHeight(60);
        $drawing2->setCoordinates("L{$row}");

        // 🔥 center-ish positioning
        $drawing2->setOffsetX(40);
        $drawing2->setOffsetY(10);

        $drawing2->setWorksheet($sheet);
    }
}
    private function injectSignName($sheet)
    {
        $row = $this->lastRow + 14;


        if (!$this->data['signname']) return;
        $sheet->setCellValue("K{$row}", "Nama : " . $this->data['signname']);


        if (!$this->data['superSignName']) return;
        $sheet->setCellValue("L{$row}", "Nama : "  . $this->data['superSignName']);

    }
    private function injectVendor($sheet)
    {
        $vendor = $this->data['vendor'];

        $sheet->setCellValue('E12', $vendor->vendorName);
        $sheet->setCellValue('E13', $vendor->alamat);
        $sheet->setCellValue('E14', $vendor->namaKontak);
        $sheet->setCellValue('E15', $vendor->nomorTelepon);
    }
    private function injectReceiver($sheet)
    {
        $po = $this->data['po'];
        $penerima = $this->data['penerima'];

        $sheet->setCellValue('K12', $po->companyName);
        $sheet->setCellValue('K13', $penerima->lokasiPengantaran);
        $sheet->setCellValue('K14', $penerima->namaPenerima);
        $sheet->setCellValue('K15', $penerima->nomorKontak);

        $sheet->getStyle('K13')
        ->getAlignment()
        ->setWrapText(true);
        }
}
