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
    <div style ="width:100%;">
        <div id='container-1' style="width:100%;height:10%">
            <img id="logo-black" src="{{public_path('/assets/img/logo-black.png')}}"> </img>        
            <img id="logo-black" style="float:right" src="{{public_path('/assets/img/logo-black.png')}}"> </img>        
        </div>
    </div>
    <table style="width:100%; border-collapse: collapse; border: none; padding:0; margin:0;">
        <tr>
            <td style="width:50%; border:none; padding:0; margin:0; vertical-align: top;">
                <table style="width:100%; border-collapse: collapse; border:none; margin:0; padding:0;">
                    <tr><td style="border:none; padding:4px;">a</td></tr>
                    <tr><td style="border:none; padding:4px;">a</td></tr>
                    <tr><td style="border:none; padding:4px;">a</td></tr>
                    <tr><td style="border:none; padding:4px;">a</td></tr>
                </table>
            </td>
            <td style="width:50%; border:none; padding:0; margin:0; vertical-align: top;">
                <table style="width:100%; border-collapse: collapse; border:none; margin:0; padding:0;">
                    <tr><td style="border:none; padding:4px;">a</td></tr>
                    <tr><td style="border:none; padding:4px;">a</td></tr>
                    <tr><td style="border:none; padding:4px;">a</td></tr>
                    <tr><td style="border:none; padding:4px;">a</td></tr>
                </table>
            </td>
        </tr>
    </table>
    <table style="width:100%; border-collapse: collapse; margin-top:10px;">
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
                            <span style="display:inline-block;vertical-align:middle;"> gitu </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px;">
                            <span style="display:inline-block; width:120px;vertical-align:middle;">Address</span>
                            <span style="display:inline-block;vertical-align:middle;">:</span>
                            <span style="display:inline-block;vertical-align:middle;"> iye</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px;">
                            <span style="display:inline-block; width:120px;vertical-align:middle;">Contact</span>: Iye
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px;">
                            <span style="display:inline-block; width:120px;vertical-align:middle;">Phone</span>: ak bom kau
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
                            <span style="display:inline-block; width:120px;vertical-align:middle;">Company Name</span>: gitu
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px;">
                            <span style="display:inline-block; width:120px;vertical-align:middle;">Address</span>: iye
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px;">
                            <span style="display:inline-block; width:120px;vertical-align:middle;">Contact</span>: Iye
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px;">
                            <span style="display:inline-block; width:120px;vertical-align:middle;">Phone</span>: ak bom kau
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
            $subtotal = 0;
            $discount = 0;
            $grandTotal = 2000000;
        @endphp
        @foreach ($po_items as $item)
            <tr>
                <td>{{ $item->itemName }}</td>
                <td style="border-right:none;">{{ $item->qty }}</td>
                <td style="border-left:none;">{{ $item->unit }}</td>
                <td>{{ $item->price }}</td>                
                <td>{{ $item->discount }}</td>
                <td>{{ $item->total }}</td>
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
                        <td style="text-align:right;">Rp {{ number_format($discount,0,',','.') }}</td>
                    </tr>
                    <tr>
                        <td><b>GRAND TOTAL</b></td>
                        <td style="text-align:right;"><b>Rp {{ number_format($grandTotal,0,',','.') }}</b></td>
                    </tr>
                </table>
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

    <h2>Data Export - {{ $record->po_number }}</h2>
</body>
</html>
