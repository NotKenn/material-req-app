<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th{background-color:gray; text-align:center;}
        #logo-black{ width:25%;}
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
                $revisi = $record->isRevised;
                $getMRCode = DB::table('mr_table')
                    ->join('po_mr', 'mr_table.id', '=', 'po_mr.mr_id')
                    ->where('po_mr.po_id', $record->id)
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
                    ->pluck('mr_table.requester_id');
        $requesters = \App\Models\requesters::where('id', $getReqID)->first();
    @endphp
        <tr>
            <!-- Kolom kiri -->
            <td style="width:50%; vertical-align: top; padding-right:15px; border:none;">
                <table style="width:100%; border-collapse: collapse; font-size:12px; border:1px solid #000;">
                    <tr>
                        <th style="background:grey; text-align:center; border:1px solid #000; padding:6px;">
                            Purchase Form
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
                            Delivery To
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
                            <span style="display:inline-block;vertical-align:middle;"> {{ $requesters->alamatPT }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px;">
                            <span style="display:inline-block; width:120px;vertical-align:middle;">Contact</span>
                            <span style="display:inline-block;vertical-align:middle;">:</span>
                            <span style="display:inline-block;vertical-align:middle;"> {{ $requesters->namaKontakPT }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px;">
                            <span style="display:inline-block; width:120px;vertical-align:middle;">Phone</span>
                            <span style="display:inline-block;vertical-align:middle;">:</span>
                            <span style="display:inline-block;vertical-align:middle;"> {{ $requesters->noTelpKontakPT }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    {{-- Table Items --}}
    <table>
        <th>Description</th>
        <th colspan="2">Qty</th>
        <th>Unit Price</th>
        <th>Discount</th>
        <th>Amount</th>
        @php
            $po_items = \App\Models\PoItems::where('po_id', $record->id)->get();
            $subtotal = \App\Models\PoItems::where('po_id', $record->id)->sum('amount');
            $discountRaw = \App\Models\PoDetails::where('id', $record->id)->value('gl_disc'); // pakai value() biar langsung string/number
            $discountValue = 0;

            // Hitung discount
            if ($discountRaw) {
                if (is_string($discountRaw) && str_contains($discountRaw, '%')) {
                    // Discount persen
                    $percent = (float) str_replace('%', '', $discountRaw);
                    $discountValue = ($percent / 100) * $subtotal;
                } else {
                    // Discount nominal (buang non-digit biar aman)
                    $discountValue = (float) preg_replace('/[^0-9]/', '', (string) $discountRaw);
                }
            }
            $grandTotal = $subtotal - $discountValue;
            
            if ($grandTotal < 0) {
                $grandTotal = 0;
            }
        @endphp
        @foreach ($po_items as $item)
            <tr>
                <td>{{ $item->itemName }}</td>
                <td style="border-right:none;">{{ $item->qty }}</td>
                <td style="border-left:none;">{{ $item->unit }}</td>
                <td>Rp. {{ number_format($item->price,0,',','.') }}</td>                
                <td>{{ number_format($item->discount,0,',','.') }}</td>
                <td>Rp {{ number_format($item->total,0,',','.') }}</td>
            </tr>
            {{-- foreach untuk data dari po_items where po_id itu $record->id, gk tau gmn cara ambilnya --}}
        @endforeach
    </table>
    {{-- Remarks --}}
    <table style="width:100%; border-collapse: collapse; margin-top:10px;" border="1">
        <tr>
            <td style="width:62.5%; vertical-align:top; padding:5px;font-size:11px;">
                <b>REMARKS:</b><br>
                * Pembayaran akan dilakukan oleh nama PT yang tertera di Company Name <br>
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
                        <td style="text-align:right;">Rp {{ number_format($discountValue,0,',','.') }}</td>
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
                <div style="height:50px;"></div>
                {{ auth()->user()->name }}
            </td>
            <td style="text-align: center; vertical-align: bottom; border:1px solid black;">
                <!-- tanda tangan acknowledged -->
                <div style="height:50px;"></div>
                Purchasing Manager
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
