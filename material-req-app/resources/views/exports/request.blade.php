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
            <div style="font-size:22px; font-weight:bold;">
                Material Request
            </div>
        </td>

        <!-- Judul kanan -->
        <td style="width:33%; text-align:center; vertical-align:top;border:none; padding:5px">
            <div style="font-size:16px; font-weight:bold;">
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
        $getPenerimaID = DB::table('mr_table')
                        ->where('id', $record->id)
                        ->pluck('penerima_id')
                        ->first();
        $penerima = \App\Models\penerima::where('id', $getPenerimaID)->first();

    @endphp
    </table>
    <br>
    <!-- Credentials -->
    <table style="width:100%; border-collapse:collapse; border:none;">

        <!-- ROW 1 -->
        <tr>
            <td style="width:120px; padding:4px 6px;border:none !important;">Tanggal</td>
            <td style="width:10px; text-align:center;border:none !important;;">:</td>
            <td style="padding:4px 6px; width:150px;border:none !important;">
                {{ $details->tanggal }}
            </td>

            @php
                $lokasi = $penerima->lokasiPengantaran ?? '';
                $len = strlen($lokasi);

                if ($len > 120) {
                    $fontSize = '9px';
                } elseif ($len > 80) {
                    $fontSize = '10px';
                } else {
                    $fontSize = '12px';
                }
            @endphp

            <td style="width:160px; padding:4px 6px;border:none !important;">Lokasi Pengantaran</td>
            <td style="width:10px; text-align:center;border:none !important;">:</td>
            <td style="
                padding:4px 6px;
                word-break:break-word;
                white-space:normal;
                max-width:250px;
                border:none !important;
                font-size: {{ $fontSize }};
            ">
                {{ $lokasi }}
            </td>
        </tr>

        <!-- ROW 2 -->
        <tr>
            <td style="padding:4px 6px;border:none !important;">Nama PT</td>
            <td style="text-align:center;border:none !important;">:</td>
            <td style="padding:4px 6px;border:none !important;">
                {{ $getReqName->namaPT }}
            </td>

            <td style="padding:4px 6px;border:none !important;">Nama Penerima</td>
            <td style="text-align:center;border:none !important;">:</td>
            <td style="padding:4px 6px;border:none !important;">
                {{ $getReqName->namaKontakPT }}
            </td>
        </tr>

        <!-- ROW 3 -->
        <tr>
            <td style="padding:4px 6px;border:none !important;">Departemen</td>
            <td style="text-align:center;border:none !important;">:</td>
            <td style="padding:4px 6px;border:none !important;">
                {{ $record->departemen }}
            </td>

            <td style="padding:4px 6px;border:none !important;">No. HP</td>
            <td style="text-align:center;border:none !important;">:</td>
            <td style="padding:4px 6px;border:none !important;">
                {{ $getReqName->noTelpKontakPT }}
            </td>
        </tr>

        <!-- ROW 4 -->
        <tr>
            <td style="padding:4px 6px;border:none !important;">Diperlukan tanggal</td>
            <td style="text-align:center;border:none !important;">:</td>
            <td style="padding:4px 6px;border:none !important;">
                {{ $details->tanggalPerlu }}
            </td>

            <td style="padding:4px 6px;border:none !important;">Lampiran (*Terlampir/Tidak)</td>
            <td style="text-align:center;border:none !important;">:</td>
            <td style="padding:4px 6px;border:none !important;">

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
    {{-- Notes dari table masukin ke sini --}}
    @php
        $getNotes = DB::table('mr_details')->where('mr_ids', $record->id)->value('notes');
    @endphp
    <p>Notes : {{ $getNotes }}</p>

    <table style="width: 100%; margin-top: 40px; text-align: center; border: none;">
    @php
        $creator = DB::table('users')->where('id', $record?->user_id)->first();
        $creatorSignature = $creator?->signature
            ? storage_path('app/public/'.$creator->signature)
            : null;

        $approvals = \App\Models\approvals::where('approvable_id', $record->id)
                    ->where('approvable_type', \App\Models\MatRequest::class)
                    ->latest('approved_at')
                    ->first();

        $supervisor = DB::table('users')->where('id', $approvals?->user_id)->first();
        $supervisorSignature = $supervisor?->signature
            ? storage_path('app/public/'.$supervisor->signature)
            : null;

            // buat check null, klo null kosongin aja
        $getPO = DB::table('po_mr')->where('mr_id', $record?->id)->first();
        $getPOuser = \App\Models\PoDetails::where('id', $getPO?->po_id)->first();
        $getUserID = \App\Models\User::where('id',$getPOuser?->user_id)->first();
        $getSign = $getUserID?->signature
            ? storage_path('app/public/'.$getUserID?->signature)
            : null;
    @endphp
    <tr>
        <td style="width: 33%; border: none;text-align: center">
            Pemohon Ybs,<br><br>
            <img style="height:120px;width:150px" src={{ $creatorSignature }}> </img><br>
            Nama :<b><u>{{ $creator?->name }}</u></b> <br>
            <u>{{ $record?->created_at }}</u>
        </td>
        <td style="width: 33%; border: none;text-align: center">
            Disetujui Atasan,<br><br>
            <img style="height:120px;width:150px" src={{ $supervisorSignature }}> </img><br>
           Nama :<b><u>{{ $supervisor?->name}}</u></b> <br>
            <u> {{ $approvals?->approved_at }} </u>
        </td>
        <td style="width: 33%; border: none;text-align: center">
            Diproses,<br><br>
            <img style="height:120px;width:150px" src={{ $getSign }}> </img><br>
            Nama : <b><u>{{$getUserID?->name}}</u></b> <br>
            <u>{{ $getPOuser?->created_at }} </u>
        </td>
    </tr>
</table>
{{-- Attachment Section --}}
@php
    $details = \App\Models\mrDetails::where('mr_ids', $record->id)?->first();

    $attachments = [];

    if ($details && $details->lampiran) {
        $attachments = is_array($details->lampiran)
            ? $details->lampiran
            : json_decode($details->lampiran, true);
    }
@endphp

@if (!empty($attachments))
    <div style="page-break-before: always;"></div>
    <h3 style="text-align: center;">Lampiran</h3>

    @foreach ($attachments as $index => $path)
        @php
            $absolutePath = storage_path('app/private/' . $path);
            $extension = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));
        @endphp

         <div style="text-align:center; margin-top:10px;">
            <h4>Lampiran {{ $index + 1 }}</h4>

            @if (in_array($extension, ['jpg', 'jpeg', 'png']))
                <img
                    src="data:image/{{ $extension }};base64,{{ base64_encode(file_get_contents($absolutePath)) }}"
                    alt="Lampiran {{ $index + 1 }}"
                    style="max-width: 90%; max-height: 600px; object-fit: contain;"
                >
            @elseif ($extension === 'pdf')
                <iframe
                    src="{{ url('/preview/' . $path) }}"
                    style="width: 90%; height: 600px;"
                ></iframe>
            @else
                <p>File: {{ basename($path) }}</p>
            @endif
        </div>
    @endforeach
@endif
