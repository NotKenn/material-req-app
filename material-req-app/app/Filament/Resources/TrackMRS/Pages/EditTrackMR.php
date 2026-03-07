<?php

namespace App\Filament\Resources\TrackMRS\Pages;

use App\Filament\Resources\TrackMRS\TrackMRResource;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

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
        $mr = $this->record;
        $count = $mr->increment('reject_count');
        $now = now();
        $rejectNote = $this->data['reject_note'] ?? null;
        $originalStatus = $mr->getOriginal('status');

        if (
            $originalStatus !== $mr->status &&
            in_array($mr->status, ['Rejected', 'Revision'])
        ) {
            $mr->update([
                'reject_note'  => $rejectNote,
                'rejected_at'  => $now,
                'reject_count' => $count,
            ]);

        }

        // notification
        if ($mr->wasChanged('status') || $mr->wasChanged('po_file')) {

            $recipient = \App\Models\User::find($mr->user_id);

            if ($recipient) {
                \Filament\Notifications\Notification::make()
                    ->title("MR {$mr->kodeRequest} diupdate")
                    ->body(
                        $mr->wasChanged('status')
                            ? "Status berubah menjadi {$mr->status}"
                            : "File PO diupload"
                    )
                    ->warning()
                    ->sendToDatabase($recipient);
            }
        }
    }
}
