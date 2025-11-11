<?php

use App\Models\matRequest;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
use Filament\Notifications\Notification as FilNotification;
use App\Models\User;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification as LaravelNotification;
use Illuminate\Notifications\Notifiable;
use Filament\Notifications\Notification;

// Route::get('/test-filament-notif', function () {
//     $recipient = User::find(2);
//         Notification::make()
//         ->title('Halo dari Filament!')
//         ->body('Notifikasi ini harusnya masuk ke User dengan ID 2')
//         ->sendToDatabase($recipient);
//     });

    // dd(Notification::make()
    //     ->title('Halo dari Filament!')
    //     ->body('Notifikasi ini harusnya masuk ke User dengan ID 2')
    //     ->sendToDatabase($recipient));

    // return 'sent';
// });
// Route::get('/test-filament-notif', function () {
//     $recipient = User::find(3);


//     if (! $recipient) {
//         return 'User tidak ditemukan';
//     }

//     Notification::make()
//         ->title('Halo dari Filament!')
//         ->body('Notifikasi ini harusnya masuk ke User dengan ID 3')
//         ->sendToDatabase($recipient);

//     return 'Notif dikirim ke user id 3';
// });

use app\models\PoDetails;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use iio\libmergepdf\Merger;
use setasign\Fpdi\Tcpdf\Fpdi;

// Tes PO
// Route::get('/test-pdf/{record}', function ($id) {
//     $record = PoDetails::findOrFail($id);

//     $pdf = Pdf::loadView('exports.record', [
//         'record' => $record,
//     ])->setPaper('a4');

//     return response()->stream(
//         fn () => print($pdf->output()),
//         200,
//         [
//             'Content-Type' => 'application/pdf',
//             'Content-Disposition' => "inline; filename=record-{$record->id}.pdf",
//         ]
//     );
// });

// Tes MR

Route::get('/test-pdf/{record}', function ($id) {
    $record = matRequest::findOrFail($id);

    $pdf = Pdf::loadView('exports.request', [
        'record' => $record,
    ])->setPaper('a4', 'Potrait');

    return response()->stream(
        fn () => print($pdf->output()),
        200,
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"PO-{$record->po_number}-merged.pdf\"",
        ]
    );
});

Route::get('/mr/{record}/pdf', function ($id) {
    $record = MatRequest::findOrFail($id);

    $pdf = Pdf::loadView('exports.request', compact('record'))
        ->setPaper('a4', 'Potrait');

    return response()->stream(
        fn() => print($pdf->output()),
        200,
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename=MR-{$record->kodeRequest}.pdf",
        ]
    );
})->name('mr.preview.pdf');

Route::get('/po/{record}/pdf', function ($id) {
    $record = PoDetails::findOrFail($id);
    $poNumber = $record->po_number;
    $getRelatedMR = DB::table('po_mr')
            ->where('po_id', $record->id)
            ->pluck('mr_id');
    $mrRecords = matRequest::whereIn('id',$getRelatedMR)->get();

    $files =[];

    $poPdf = Pdf::loadView('exports.record', compact('record'))
        ->setPaper('a4', 'potrait')
        ->setOption('margin-top', 0)
        ->output();

    $poPath = storage_path("app/temp_po_{$record->id}.pdf");
    file_put_contents($poPath, $poPdf);
    $files[]= $poPath;

    foreach ($mrRecords as $record) {
        $mrPdf = Pdf::loadView('exports.request', compact('record'))
            ->setPaper('a4', 'Potrait')
            ->setOption('margin-top', 0)
            ->output();

        $mrPath = storage_path("app/temp_mr_{$record->id}.pdf");
        file_put_contents($mrPath, $mrPdf);
        $files[] = $mrPath;
    }
    $merger = new Merger();

    foreach ($files as $file) {
        $merger->addFile($file);
    }
    $mergedPdf = $merger->merge();

    //hapus file
    foreach ($files as $file) {
        @unlink($file);
    }

    $tempMergedPath = storage_path("app/temp_merged_{$record->id}.pdf");
    file_put_contents($tempMergedPath, $mergedPdf);

    $finalPdf = new FPDI();
    $finalPdf->SetAutoPageBreak(false);
    $finalPdf->SetMargins(0, 0, 0);
    $finalPdf->setPrintHeader(false);
    $finalPdf->setPrintFooter(false);
    $finalPdf->SetDisplayMode('fullpage');
    $pageCount = $finalPdf->setSourceFile($tempMergedPath);

    for ($i = 1; $i <= $pageCount; $i++) {
        $template = $finalPdf->importPage($i);
        $size = $finalPdf->getTemplateSize($template);
        $finalPdf->AddPage($size['orientation']== 'L' ? 'L' : 'P', [$size['width'], $size['height']]);
        $finalPdf->useTemplate($template);
    }

    // Set PDF metadata title only
    $finalPdf->SetTitle("PO-{$poNumber}-(Merged)", true);

    unlink($tempMergedPath);

    return response(
        $finalPdf->Output('S'),
        200,
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"PO-{$poNumber}-merged.pdf\"",
        ]
    );
})->name('po.preview.pdf');
