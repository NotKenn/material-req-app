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
    <table style="width:100%; border-collapse: collapse; margin-top:10px;">
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
    @endphp
        <tr>
            <!-- Kolom kiri -->
            <td style="width:50%; vertical-align: top; padding-right:15px; border:none;">
                <table style="width:100%; border-collapse: collapse; font-size:12px; border:1px solid #000;">
                    <tr>
                        <th style="background:grey; text-align:center; border:1px solid #000; padding:6px;">
                            Purchase From
                        </th>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px;">
                            <span style="display:inline-block; width:120px;vertical-align:middle;">Company Name</span>
                            <span style="display:inline-block;vertical-align:middle;">:</span>
                            <span style="display:inline-block;vertical-align:middle;"> {{ $vendor->vendorName }} </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px;">
                            <span style="display:inline-block; width:120px;vertical-align:middle;">Address</span>
                            <span style="display:inline-block;vertical-align:middle;">:</span>
                            <span style="display:inline-block;vertical-align:middle;"> {{ $vendor->alamat }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px;">
                            <span style="display:inline-block; width:120px;vertical-align:middle;">Contact</span>
                            <span style="display:inline-block;vertical-align:middle;">:</span>
                            <span style="display:inline-block;vertical-align:middle;"> {{ $vendor->namaKontak}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px;">
                            <span style="display:inline-block; width:120px;vertical-align:middle;">Phone</span>
                            <span style="display:inline-block;vertical-align:middle;">:</span>
                            <span style="display:inline-block;vertical-align:middle;"> {{ $vendor->nomorTelepon }}</span>
                        </td>
                    </tr>
                </table>
            </td>

            <!-- Kolom kanan -->
            <td style="width:50%; vertical-align: top; padding-left:15px; border:none;">
                <table style="width:100%; border-collapse: collapse; font-size:12px; border:1px solid #000;">
                    <tr>
                        <th style="background:grey; text-align:center; border:1px solid #000; padding:6px;">
                            Deliver To
                        </th>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px;">
                            <span style="display:inline-block; width:120px;vertical-align:middle;">Company Name</span>
                            <span style="display:inline-block;vertical-align:middle;">:</span>
                            <span style="display:inline-block;vertical-align:middle;"> {{ $requesters->namaPT }} </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px;">
                            <span style="display:inline-block; width:120px;vertical-align:middle;">Address</span>
                            <span style="display:inline-block;vertical-align:middle;">:</span>
                            <span style="display:inline-block;vertical-align:middle;"> {{ $penerima->lokasiPengantaran }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px;">
                            <span style="display:inline-block; width:120px;vertical-align:middle;">Contact</span>
                            <span style="display:inline-block;vertical-align:middle;">:</span>
                            <span style="display:inline-block;vertical-align:middle;"> {{ $penerima->namaPenerima }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px;">
                            <span style="display:inline-block; width:120px;vertical-align:middle;">Phone</span>
                            <span style="display:inline-block;vertical-align:middle;">:</span>
                            <span style="display:inline-block;vertical-align:middle;"> {{ $penerima->nomorKontak }}</span>
                        </td>
                    </tr>
                </table>
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
                $discountDisplay = number_format($item->calculated_discount, 0, ',', '.') . "(". "{$percent}%" . ")";
                // dd($discountDisplay);
            } else {
                $discountDisplay = number_format($item->calculated_discount, 0, ',', '.');
            }
        @endphp

        <tr>
            <td>{{ $item->itemName }}</td>
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
            * Pembayaran akan dilakukan oleh nama PT yang tertera di "Company Name" yang tertera dibagian atas <br>
            * Setiap pengantaran barang mohon dilampirkan PO, jika tidak melampirkan PO maka barang tersebut dikembalikan oleh penerima barang <br>
            * Pada sangat pengantaran WAJIB mencantumkan kuantiti barang di Delivery Order dalam satuan sesuai PO. <br>
            * Yang berwenang menerima barang HANYA nama yang tertera diatas kolom Delivery To.
            Sebelum pengantaran mohon hubungi kepada pihak yang berwenang terlebih dahulu <br>
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
            </td>
            <td style="text-align: center; vertical-align: bottom; border:1px solid black;">
                <!-- tanda tangan acknowledged -->
                <img style="height:80px;width:100px" src={{ $supervisorSignature }}> </img><br>
                <div style="height:20px;"></div>
                <b><u>{{ $supervisor?->name }}</u><b>
            </td>
        </tr>
    </table>

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
