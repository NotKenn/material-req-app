<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class PoExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $data;
    protected $lastRow = 20;

    public function __construct($poId)
    {
        $this->data = $this->prepare($poId);
    }

    private function prepare($poId)
    {
        $po = \App\Models\PoDetails::findOrFail($poId);

        // 🔹 Vendor
        $vendor = \App\Models\Vendor::find($po->vendorID);

        // 🔹 MR Codes
        $mrCodes = DB::table('mr_table')
            ->join('po_mr', 'mr_table.id', '=', 'po_mr.mr_id')
            ->where('po_mr.po_id', $poId)
            ->pluck('mr_table.kodeRequest')
            ->toArray();

        // 🔹 Items
        $items = \App\Models\PoItems::where('po_id', $poId)->get();

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

        // 🔹 Global Discount
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

        // 🔹 Signature
        $creator = \App\Models\User::find($po->user_id);

        $signaturePath = $creator?->signature
            ? storage_path('app/public/' . $creator->signature)
            : null;

        // 🔥 RETURN SEMUA DATA (INI YANG NANTI DIPAKAI EXCEL)
        return [
            'po' => $po,
            'vendor' => $vendor,
            'mr_codes' => implode(', ', $mrCodes),
            'items' => $items,

            'totals' => [
                'subtotal' => $subtotal,
                'discount' => $totalDiscount,
                'grand' => $grandTotal
            ],

            'signature' => $signaturePath
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

        $sheet->setCellValue('K6', $vendor->vendorName);
        $sheet->setCellValue('K7', $po->date);
        $sheet->setCellValue('K8', $po->contactName);
        $sheet->setCellValue('K9', $po->phone);

        // MR Codes
        $sheet->setCellValue('K10', $this->data['mr_codes']);

        // wrap text kalau panjang
        $sheet->getStyle('F7')->getAlignment()->setWrapText(true);
    }
    private function injectItems($sheet)
    {
        $items = $this->data['items'];

        $startRow = 18; // sesuai template kamu
        $currentRow = $startRow;

        foreach ($items as $index => $item) {

            if ($index > 0) {
                $sheet->insertNewRowBefore($currentRow, 1);

                // copy style dari row template
                $sheet->duplicateStyle(
                    $sheet->getStyle("A{$startRow}:L{$startRow}"),
                    "A{$currentRow}:L{$currentRow}"
                );
            }

            $sheet->setCellValue("C{$currentRow}", $item->note);
            $sheet->setCellValue("H{$currentRow}", $item->qty);
            $sheet->setCellValue("I{$currentRow}", $item->unit);

            $sheet->setCellValue("K{$currentRow}", $item->price);
            $sheet->setCellValue("L{$currentRow}", $item->final_total);

            $currentRow++;
        }

        // simpan posisi terakhir buat totals
        $this->lastRow = $currentRow;
    }
    private function injectTotals($sheet)
    {
        $totals = $this->data['totals'];

        $row = $this->lastRow + 1;

        $sheet->setCellValue("D{$row}", 'SUBTOTAL');
        $sheet->setCellValue("E{$row}", $totals['subtotal']);

        $sheet->setCellValue("D" . ($row + 1), 'DISCOUNT');
        $sheet->setCellValue("E" . ($row + 1), $totals['discount']);

        $sheet->setCellValue("D" . ($row + 2), 'GRAND TOTAL');
        $sheet->setCellValue("E" . ($row + 2), $totals['grand']);
    }
    private function injectSignature($sheet)
    {
        if (!$this->data['signature']) return;

        $drawing = new Drawing();
        $drawing->setPath($this->data['signature']);
        $drawing->setHeight(60);
        $drawing->setCoordinates('B' . ($this->lastRow + 5));
        $drawing->setWorksheet($sheet);
    }
    public function collection()
    {
        //
    }
}
