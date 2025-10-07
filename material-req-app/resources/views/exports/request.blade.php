<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Material Request</title>
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
        <td style="width:33%; text-align:left; vertical-align:top;border:none; padding:5px">
        </td>

        <<!-- Judul Tengah -->
        <td style="width:33%; text-align:center; vertical-align:top;border:none; padding:5px">
            <div style="font-size:24px; font-weight:bold;">
                Material Request
            </div>
        </td>
        
        <!-- Judul kanan -->
        <td style="width:33%; text-align:center; vertical-align:top;border:none; padding:5px">
            <div style="font-size:18px; font-weight:bold;">
                MR No. : {{ $record->kodeRequest }}
                
            </div>
        </td>
    </tr>
    @php
        $getReqName = DB::table('mr_table')
            ->join('requesters', 'mr_table.requester_id', '=', 'requesters.id')
            ->where('mr_table.id', $record->id) // ambil MR tertentu 
            ->first();
            // ->value('requesters.namaPT'); // langsung value kalau cuma satu
        
        $details = \App\Models\mrDetails::where('mr_ids', $record->id)->first();
    @endphp
    </table>
    <br>
    <!-- Credentials -->
    <table style="width:100%; border-collapse: collapse; border: none; padding:0; margin:0;">
        <tr>
            <td style="width:50%; border:none; padding:0; margin:0; vertical-align: top;">
                <table style="width:100%; border-collapse: collapse; border:none; margin:0; padding:0;">
                    <tr>
                        <td style="border:none; padding:4px;">
                            <span style="display:inline-block; width:120px;vertical-align:middle;">Tanggal</span>
                            <span style="display:inline-block;vertical-align:middle;">:</span>
                            <span style="display:inline-block;vertical-align:middle;"> {{ $details->tanggal }} </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:none; padding:4px;">
                            <span style="display:inline-block; width:120px;vertical-align:middle;">Nama PT</span>
                            <span style="display:inline-block;vertical-align:middle;">:</span>
                            <span style="display:inline-block;vertical-align:middle;"> {{ $getReqName->namaPT }} </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:none; padding:4px;">
                            <span style="display:inline-block; width:120px;vertical-align:middle;">Departemen</span>
                            <span style="display:inline-block;vertical-align:middle;">:</span>
                            <span style="display:inline-block;vertical-align:middle;"> {{ $record->departemen }} </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:none; padding:4px;">
                            <span style="display:inline-block; width:120px;vertical-align:middle;">Diperlukan tanggal</span>
                            <span style="display:inline-block;vertical-align:middle;">:</span>
                            <span style="display:inline-block;vertical-align:middle;"> {{ $details->tanggalPerlu }} </span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width:50%; border:none; padding:0; margin:0; vertical-align: top;">
                <table style="width:100%; border-collapse: collapse; border:none; margin:0; padding:0;">
                    <tr>
                        <td style="border:none; padding:4px;">
                            <span style="display:inline-block; width:175px;vertical-align:middle;">Lokasi Pengantaran</span>
                            <span style="display:inline-block;vertical-align:middle;">:</span>
                            <span style="display:inline-block;vertical-align:middle;"> {{ $details->lokasiPengantaran }} </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:none; padding:4px;">
                            <span style="display:inline-block; width:175px;vertical-align:middle;">Nama Penerima Barang</span>
                            <span style="display:inline-block;vertical-align:middle;">:</span>
                            <span style="display:inline-block;vertical-align:middle;"> {{ $getReqName->namaKontakPT }} </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:none; padding:4px;">
                            <span style="display:inline-block; width:175px;vertical-align:middle;">No. Hp Penerima Barang</span>
                            <span style="display:inline-block;vertical-align:middle;">:</span>
                            <span style="display:inline-block;vertical-align:middle;"> {{ $getReqName->noTelpKontakPT }} </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:none; padding:4px;">
                            <span style="display:inline-block; width:175px;vertical-align:middle;">Lampiran (*Terlampir/Tidak)</span>
                            <span style="display:inline-block;vertical-align:middle;">:</span>
                            {{-- <span style="display:inline-block;vertical-align:middle;"> {{ $record->termOfPayment }} </span> --}}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    {{-- items --}}
    <table>
        <th style="width:5%">No.</th>
        <th style="width:40%">Nama Detail Barang</th>
        <th style="width:10%" colspan=2>Qty</th>
        <th style="width:45%">Note</th>
        @php
            $mr_items = \App\Models\matRequestItems::where('mr_id', $record->id)->get();

        @endphp
        @foreach ($mr_items as $index => $item)
            <tr>

                <td style="text-align:center">{{ $index + 1 }}</td>
                <td>{{ $item->itemName }}</td>
                <td style="border-right:none;text-align:center">{{ $item->Qty }}</td>
                <td style="border-left:none;text-align:center">{{ $item->satuan }}</td>
                <td style="text-align:center">{{ $item->notes }}</td>

            </tr>
            {{-- foreach untuk data dari po_items where po_id itu $record->id, gk tau gmn cara ambilnya --}}
        @endforeach
    </table>
    <table style="width: 100%; margin-top: 40px; text-align: center; border: none;">
    @php
        $creator = DB::table('users')->where('id', $record->user_id)->first();
        $creatorSignature = $creator?->signature 
            ? storage_path('app/public/'.$creator->signature)
            : null;
        
        $approvals = \App\Models\approvals::where('approvable_id', $record->id)
                    ->where('approvable_type', \App\Models\MatRequest::class)
                    ->latest('approved_at')
                    ->first();
        
        $supervisor = DB::table('users')->where('id', $approvals->user_id)->first();
        $supervisorSignature = $supervisor?->signature 
            ? storage_path('app/public/'.$supervisor->signature)
            : null;

            //buat check null, klo null kosongin aja
        // $getPO = DB::table('po_mr')->where('mr_id', $record->id)->first();
        // $getPOuser = \App\Models\PoDetails::where('id', $getPO->po_id)->first();
        // $getUserID = \App\Models\User::where('id',$getPOuser?->user_id)->first();
        // $getSign = $getUserID->signature
        //     ? storage_path('app/public/'.$getUserID->signature)
        //     : null;
    @endphp
    <tr>
        <td style="width: 33%; border: none;text-align: center">
            Pemohon Ybs,<br><br>
            <img style="height:120px;width:150px" src={{ $creatorSignature }}> </img><br>
            Nama :<b><u>{{ $creator->name }}</u></b>
        </td>
        <td style="width: 33%; border: none;text-align: center">
            Disetujui Atasan,<br><br>
            <img style="height:120px;width:150px" src={{ $supervisorSignature }}> </img><br>
            Nama :<b><u>{{ $supervisor->name}}</u></b>
        </td>
        <td style="width: 33%; border: none;text-align: center">
            Diproses,<br><br>
            <img style="height:120px;width:150px" src={{ $supervisorSignature }}> </img><br>
            Nama : <b><u></u></b>
        </td>
    </tr>
</table> 
