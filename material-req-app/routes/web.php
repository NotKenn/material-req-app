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
    ])->setPaper('a4', 'Landscape');

    return response()->stream(
        fn () => print($pdf->output()),
        200,
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename=record-{$record->id}.pdf",
        ]
    );
});
