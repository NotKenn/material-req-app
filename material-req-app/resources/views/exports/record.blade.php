<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
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
                        <th style="background:#ddd; text-align:center; border:1px solid #000; padding:6px;">
                            Delivery To
                        </th>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px;">
                            <span style="display:inline-block; width:120px;">Company Name</span>: gitu
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px;">
                            <span style="display:inline-block; width:120px;">Address</span>: iye
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px;">
                            <span style="display:inline-block; width:120px;">Contact</span>: Iye
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px;">
                            <span style="display:inline-block; width:120px;">Phone</span>: ak bom kau
                        </td>
                    </tr>
                </table>
            </td>

            <!-- Kolom kanan -->
            <td style="width:50%; vertical-align: top; padding-left:15px; border:none;">
                <table style="width:100%; border-collapse: collapse; font-size:12px; border:1px solid #000;">
                    <tr>
                        <th style="background:#ddd; text-align:center; border:1px solid #000; padding:6px;">
                            Bill To
                        </th>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px;">
                            <span style="display:inline-block; width:120px;">Company Name</span>: gitu
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px;">
                            <span style="display:inline-block; width:120px;">Address</span>: iye
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px;">
                            <span style="display:inline-block; width:120px;">Contact</span>: Iye
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px;">
                            <span style="display:inline-block; width:120px;">Phone</span>: ak bom kau
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    {{-- Table Items --}}
    <table>
        <th>Description</th>
        <th>Qty</th>
        <th>Unit Price</th>
        <th>Amount</th>
        @foreach ( as )
            {{-- foreach untuk data dari po_items where po_id itu $record->id, gk tau gmn cara ambilnya --}}
        @endforeach
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
