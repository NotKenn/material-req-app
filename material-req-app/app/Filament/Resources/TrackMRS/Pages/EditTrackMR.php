<?php

namespace App\Filament\Resources\TrackMRS\Pages;

use App\Filament\Resources\TrackMRS\TrackMRResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditTrackMR extends EditRecord
{
    protected static string $resource = TrackMRResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function afterSave(): void
    {
    $mr = $this->record; // record MR yang baru saja diupdate

    if ($mr->wasChanged('status') || $mr->wasChanged('po_file')) {
        $recipient = User::find($mr->user_id); // ambil user berdasarkan user_id
        // dd($recipient);

        if ($recipient) {
            Notification::make()
                ->title("MR {$mr->kodeRequest} diupdate")
                ->body(
                    $mr->wasChanged('status')
                        ? "Status berubah menjadi {$mr->status}"
                        : "File PO diupload"
                )
                ->sendToDatabase($recipient);
        }
    }
}
}
