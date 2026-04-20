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
                            <span style="display:inline-block;vertical-align:middle;"> {{ $penerima->lokasiPengantaran }} </span>
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
