<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchasing Order</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th{background-color:gray; text-align:center;}
        #logo-black{ width:25%;}
        .td-money {
        text-align: right;
        }
        .td-money span {
            float: left;
        }
    </style>
</head>
<body>
    <table style="width:100%; border-collapse:collapse;so-border-alt:none; border:none">
    <tr>
        <!-- Logo kiri -->
        <td style="width:70%; text-align:left; vertical-align:top;border:none; padding:5px">
            <img src="{{ public_path('/assets/img/logo-black.png') }}" style="height:50px;">
        </td>

        <!-- Judul kanan -->
        <td style="width:30%; text-align:right; vertical-align:top;border:none; padding:5px">
            <div style="font-size:18px; font-weight:bold; text-decoration:underline;">
                PURCHASE ORDER
            </div>
            @php
                $revisi = $record?->isRevised;
                $getMRCode = DB::table('mr_table')
                    ->join('po_mr', 'mr_table.id', '=', 'po_mr.mr_id')
                    ->where('po_mr.po_id', $record?->id)
                    ->pluck('mr_table.kodeRequest');
            @endphp
            @if ($revisi === 'Yes')
                <div style="font-size:12px; margin-top:2px;">
                    Revisi
                </div>
            @else
                <div style="font-size:12px; margin-top:2px;">

                </div>
            @endif
        </td>
    </tr>
    </table>
    <table style="width:100%; border-collapse: collapse; border: none; padding:0; margin:0;">
        <tr>
            <td style="width:50%; border:none; padding:0; margin:0; vertical-align: top;">
                <table style="width:100%; border-collapse: collapse; border:none; margin:0; padding:0;">
                    <tr>
                        <td style="border:none; padding:4px;">
                            <span style="display:inline-block; width:120px;vertical-align:middle;">Company Name</span>
                            <span style="display:inline-block;vertical-align:middle;">:</span>
                            <span style="display:inline-block;vertical-align:middle;"> {{ $record->companyName }} </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:none; padding:4px;">
                            <span style="display:inline-block; width:120px;vertical-align:middle;">Office Address</span>
                            <span style="display:inline-block;vertical-align:middle;">:</span>
                            <span style="display:inline-block;vertical-align:middle;"> {{ $record->officeAddress }} </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:none; padding:4px;">
                            <span style="display:inline-block; width:120px;vertical-align:middle;">Contact Name</span>
                            <span style="display:inline-block;vertical-align:middle;">:</span>
                            <span style="display:inline-block;vertical-align:middle;"> {{ $record->contactName }} </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:none; padding:4px;">
                            <span style="display:inline-block; width:120px;vertical-align:middle;">Phone</span>
                            <span style="display:inline-block;vertical-align:middle;">:</span>
                            <span style="display:inline-block;vertical-align:middle;"> {{ $record->phone }} </span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width:50%; border:none; padding:0; margin:0; vertical-align: top;">
                <table style="width:100%; border-collapse: collapse; border:none; margin:0; padding:0;">
                    <tr>
                        <td style="border:none; padding:4px;">
                            <span style="display:inline-block; width:120px;vertical-align:middle;">PO No.</span>
                            <span style="display:inline-block;vertical-align:middle;">:</span>
                            <span style="display:inline-block;vertical-align:middle;"> {{ $record->po_number }} </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:none; padding:4px;">
                            <span style="display:inline-block; width:120px;vertical-align:middle;">Date</span>
                            <span style="display:inline-block;vertical-align:middle;">:</span>
                            <span style="display:inline-block;vertical-align:middle;"> {{ $record->date }} </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:none; padding:4px;">
                            <span style="display:inline-block; width:120px;vertical-align:middle;">MR/SR No.</span>
                            <span style="display:inline-block;vertical-align:middle;">:</span>
                            <span style="display:inline-block;vertical-align:middle;"> {{ $getMRCode->join(', ') }} </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:none; padding:4px;">
                            <span style="display:inline-block; width:120px;vertical-align:middle;">Term of Payment</span>
                            <span style="display:inline-block;vertical-align:middle;">:</span>
                            <span style="display:inline-block;vertical-align:middle;"> {{ $record->termOfPayment }} </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
<table style="width:100%; style="border-collapse: separate; border-spacing: 0; margin-top:10px; font-size:12px; border:1px solid #000;">

    @php
        $vendor = \App\Models\vendor::where('id', $record->vendorID)->first();

        $getReqID = DB::table('mr_table')
                    ->join('po_mr', 'mr_table.id', '=', 'po_mr.mr_id')
                    ->where('po_mr.po_id', $record->id)
                    ->pluck('mr_table.requester_id')
                    ->first();

        $requesters = \App\Models\requesters::where('id', $getReqID)->first();

        $getPenerimaID = DB::table('mr_table')
                        ->join('po_mr', 'mr_table.id', '=', 'po_mr.mr_id')
                        ->where('po_mr.po_id', $record->id)
                        ->pluck('mr_table.penerima_id')
                        ->first();

        $penerima = \App\Models\penerima::where('id', $getPenerimaID)->first();

        // FONT LOGIC
        $alamatVendor = $vendor->alamat ?? '';
        $lenVendor = strlen($alamatVendor);
        $fontVendor = $lenVendor > 120 ? '9px' : ($lenVendor > 80 ? '10px' : '12px');

        $alamatKirim = $penerima->lokasiPengantaran ?? '';
        $lenKirim = strlen($alamatKirim);
        $fontKirim = $lenKirim > 120 ? '9px' : ($lenKirim > 80 ? '10px' : '12px');
    @endphp

    <!-- HEADER -->
    <tr>
        <th colspan="3" style="background:grey; text-align:center; border:1px solid #000; padding:6px;">
            Purchase From
        </th>

        <!-- spacer -->
        <th style="border:none; width:20px; background:none; border-top:none !important"></th>

        <th colspan="3" style="background:grey; text-align:center; border:1px solid #000; padding:6px;">
            Deliver To
        </th>
    </tr>

    <!-- ROW 1 -->
    <tr>
        <td style="width:100px; border:1px solid #000; padding:6px; border-right:none;">Company Name</td>
        <td style="width:10px; border:1px solid #000; text-align:center; border-right:none; border-left:none">:</td>
        <td style="width:140px; border:1px solid #000; padding:6px; border-left:none">
            {{ $vendor->vendorName }}
        </td>

        <td style="border:none;"></td>

        <td style="width:100px; border:1px solid #000; padding:6px; border-right:none;">Company Name</td>
        <td style="width:10px; border:1px solid #000; text-align:center; border-right:none; border-left:none">:</td>
        <td style="width:140px;border:1px solid #000; padding:6px; border-left:none">
            {{ $requesters->namaPT }}
        </td>
    </tr>

    <!-- ROW 2 -->
    <tr>
        <td style="border:1px solid #000; padding:6px; border-right:none;">Address</td>
        <td style="width:10px; border:1px solid #000; text-align:center; border-right:none; border-left:none">:</td>
        <td style="border:1px solid #000; padding:6px; word-break:break-word; border-left:none; font-size: {{ $fontVendor }};">
            {{ $alamatVendor }}
        </td>

        <td style="border:none;"></td>

        <td style="border:1px solid #000; padding:6px; border-right:none;">Address</td>
        <td style="width:10px; border:1px solid #000; text-align:center; border-right:none; border-left:none">:</td>
        <td style="border:1px solid #000; padding:6px; word-break:break-word; border-left:none; font-size: {{ $fontKirim }};">
            {{ $alamatKirim }}
        </td>
    </tr>

    <!-- ROW 3 -->
    <tr>
        <td style="border:1px solid #000; padding:6px; border-right:none;">Contact</td>
        <td style="width:10px; border:1px solid #000; text-align:center; border-right:none; border-left:none">:</td>
        <td style="border:1px solid #000; padding:6px; border-left:none">
            {{ $vendor->namaKontak }}
        </td>

        <td style="border:none;"></td>

        <td style="border:1px solid #000; padding:6px; border-right:none;">Contact</td>
        <td style="width:10px; border:1px solid #000;text-align:center; border-right:none; border-left:none">:</td>
        <td style="border:1px solid #000; padding:6px; border-left:none">
            {{ $penerima->namaPenerima }}
        </td>
    </tr>

    <!-- ROW 4 -->
    <tr>
        <td style="border:1px solid #000; padding:6px; border-right:none;">Phone</td>
        <td style="width:10px; border:1px solid #000; text-align:center; border-right:none; border-left:none">:</td>
        <td style="border:1px solid #000; padding:6px; border-left:none">
            {{ $vendor->nomorTelepon }}
        </td>

        <td style="border:none;"></td>

        <td style="border:1px solid #000; padding:6px; border-right:none;">Phone</td>
        <td style="width:10px; border:1px solid #000; text-align:center; border-right:none; border-left:none">:</td>
        <td style="border:1px solid #000; padding:6px; border-left:none">
            {{ $penerima->nomorKontak }}
        </td>
    </tr>

</table>
{{-- Table Items --}}
<table>
    <thead>
        <tr>
            <th>Description</th>
            <th colspan="2">Qty</th>
            <th colspan="2">Unit Price</th>
            <th colspan="2">Discount</th>
            <th colspan="2">Amount</th>
        </tr>
    </thead>
    <tbody>
@php
    $po_items = \App\Models\PoItems::where('po_id', $record->id)->get();
    $subtotal = 0;
    $totalItemDiscount = 0;

    foreach ($po_items as $item) {
        $itemSubtotal = $item->price * $item->qty;
        $discountRaw = trim((string) ($item->discount ?? ''));
        $discountValue = 0;

        if ($discountRaw !== '') {
            if (str_contains($discountRaw, '%')) {
                // Diskon persen
                $percent = (float) str_replace('%', '', $discountRaw);
                $discountValue = ($percent / 100) * $itemSubtotal;
            } else {
                // Diskon nominal
                $discountValue = (float) preg_replace('/[^0-9.]/', '', $discountRaw);
            }
        }

        $item->calculated_discount = $discountValue;
        $item->final_total = $itemSubtotal - $discountValue;

        $subtotal += $itemSubtotal;
        $totalItemDiscount += $discountValue;
    }

    // === DISKON GLOBAL (optional) ===
    $globalDiscRaw = \App\Models\PoDetails::where('id', $record->id)->value('gl_disc');
    $globalDiscValue = 0;

    if ($globalDiscRaw) {
        $globalDiscRaw = trim((string) $globalDiscRaw);
        if (str_contains($globalDiscRaw, '%')) {
            $percent = (float) str_replace('%', '', $globalDiscRaw);
            // Diskon global dihitung dari subtotal SETELAH diskon item
            $globalDiscValue = ($percent / 100) * ($subtotal - $totalItemDiscount);
        } else {
            $globalDiscValue = (float) preg_replace('/[^0-9.]/', '', $globalDiscRaw);
        }
    }

    $totalDiscount = $totalItemDiscount + $globalDiscValue;
    $grandTotal = max($subtotal - $totalDiscount, 0);
@endphp


    @foreach ($po_items as $item)
        @php
            $discountRaw = trim((string) ($item->discount ?? ''));
            if ($discountRaw === '') {
                $discountDisplay = '-';
            } elseif (str_contains($discountRaw, '%')) {
                $percent = (float) str_replace('%', '', $discountRaw);
                $discountDisplay = number_format($item->calculated_discount, 0, ',', '.') ." ". "(". "{$percent}%" . ")";
                // dd($discountDisplay);
            } else {
                $discountDisplay = number_format($item->calculated_discount, 0, ',', '.');
            }
        @endphp

        <tr>
            <td>{{ $item->note }}</td>
            <td style="border-right:none;">{{ $item->qty }}</td>
            <td style="border-left:none;">{{ $item->unit }}</td>

            <!-- Unit Price -->
            <td style="border-right:none;text-align:left;width:20px" class="td-money">
                Rp.
            </td>
            <td style="border-left:none;" class="td-money">
                {{ number_format($item->price, 0, ',', '.') }}
            </td>

            <!-- Discount -->
            <td style="border-right:none;text-align:left;width:20px" class="td-money">
                Rp.
            </td>
            <td style="border-left:none;max-width:4rem" class="td-money">
                    {{ $discountDisplay }}
            </td>

            <!-- Amount -->
            <td style="border-right:none;text-align:left;width:20px" class="td-money">
                Rp.
            </td>
            <td style="border-left:none;" class="td-money">
                {{ number_format($item->final_total, 0, ',', '.') }}
            </td>
        </tr>
        {{-- <tr>
            <td>{{ $item->itemName }}</td>
            <td style="border-right:none;">{{ $item->qty }}</td>
            <td style="border-left:none;">{{ $item->unit }}</td>
            <td style="float:right"><span>Rp.</span>{{ number_format($item->price, 0, ',', '.') }}</div>
            </td>
            <td> {{ $discountDisplay }}</td>
            <td><span>Rp.</span> {{ number_format($item->final_total, 0, ',', '.') }}</td>
        </tr> --}}
    @endforeach
    </tbody>
</table>
{{-- Remarks & Totals --}}
<table style="width:100%; border-collapse: collapse; margin-top:10px;" border="1">
    <tr>
        <td style="width:62.5%; vertical-align:top; padding:5px;font-size:11px;">
            <b>REMARKS:</b><br>
            {!! nl2br(e($record->remarks)) !!}

            {{-- * Pembayaran akan dilakukan oleh nama PT yang tertera di "Company Name" yang tertera dibagian atas <br>
            * Setiap Delivery Order wajib dilampirkan PO, jika tidak melampirkan PO maka barang harus dikembalikan <br>
            * Pada saat pengantaran wajib mencantumkan kuantiti barang di Delivery Order yang sesuai dengan PO. <br>
            * Yang berwenang menerima barang hanya nama yang tertera diatas kolom Deliver To. <br><br>
            Sebelum pengantaran mohon hubungi contact person penerima terlebih dahulu <br> --}}
        </td>
        <td style="width:36.5%; padding:5px; max-width:36.5%">
            <table style="width:100%; border-collapse: collapse;" border="1">
                <tr>
                    <td>SUBTOTAL</td>
                    <td style="text-align:right;">Rp {{ number_format($subtotal,0,',','.') }}</td>
                </tr>
                <tr>
                    <td>DISCOUNT</td>
                    <td style="text-align:right;">Rp {{ number_format($totalDiscount ,0,',','.') }}</td>
                </tr>
                <tr>
                    <td><b>GRAND TOTAL</b></td>
                    <td style="text-align:right;"><b>Rp {{ number_format($grandTotal,0,',','.') }}</b></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
    <table width="100%" style="margin-top: 15px; border-collapse: collapse; font-size: 12px;">
        @php
            $creator = DB::table('users')->where('id', $record?->user_id)->first();
            $creatorSignature = $creator?->signature
                ? storage_path('app/public/'.$creator?->signature)
                : null;

            $approvals = \App\Models\approvals::where('approvable_id', $record->id)
                        ->where('approvable_type', \App\Models\PoDetails::class)
                        ->latest('approved_at')
                        ->first();

            $supervisor = DB::table('users')->where('id', $approvals?->user_id)->first();
            $supervisorSignature = $supervisor?->signature
                ? storage_path('app/public/'.$supervisor?->signature)
                : null;

            // $getMonth= now()->month;
            // $getYear = now()->year;
        @endphp
        <tr>
            <td style="width: 62.5%; border:none; vertical-align: top;">
                <b>Key In By :</b>
            </td>
            <td style="width: 18.25%; text-align: center; border:1px solid black;background:gray">
                Prepared By
            </td>
            <td style="width: 18.25%; text-align: center; border:1px solid black;background:gray">
                Acknowledged By
            </td>
        </tr>
        <tr style="height: 70px;">
            <td style="border:none;"></td>
            <td style="text-align: center; vertical-align: bottom; border:1px solid black;">
                <!-- tanda tangan prepared -->
                <img style="height:80px;width:100px" src={{ $creatorSignature }}> </img><br>
                <div style="height:20px;"></div>
                <b><u>{{ auth()->user()->name }}</u></b>
                {{-- {{ $getMonth }}
                {{ $getYear }} --}}
            </td>
            <td style="text-align: center; vertical-align: bottom; border:1px solid black;">
                <!-- tanda tangan acknowledged -->
                <img style="height:80px;width:100px" src={{ $supervisorSignature }}> </img><br>
                <div style="height:20px;"></div>
                <b><u>{{ $supervisor?->name }}</u><b>
                </td>
            </tr>
        </table>
        <u style = "float:right">{{ \Carbon\Carbon::parse($record->created_at)->format('l, d/m/Y') }}</u>



        {{-- <div style="display: flex; gap: 10px; background: #eee; padding: 10px;">
            <!-- Kolom 1 -->
            <div style="flex: 1; background: lightblue; padding: 10px; display: flex; flex-direction: column; gap: 5px;">
                <div style="background: rgba(255,255,255,0.4); padding: 5px;">a</div>
                <div style="background: rgba(255,255,255,0.4); padding: 5px;">a</div>
                <div style="background: rgba(255,255,255,0.4); padding: 5px;">a</div>
                <div style="background: rgba(255,255,255,0.4); padding: 5px;">a</div>
            </div>

            <!-- Kolom 2 -->
            <div style="flex: 1; background: lightblue; padding: 10px; display: flex; flex-direction: column; gap: 5px;">
                <div style="background: rgba(255,255,255,0.4); padding: 5px;">a</div>
                <div style="background: rgba(255,255,255,0.4); padding: 5px;">a</div>
                <div style="background: rgba(255,255,255,0.4); padding: 5px;">a</div>
                <div style="background: rgba(255,255,255,0.4); padding: 5px;">a</div>
            </div>
        </div> --}}
</body>
</html>
