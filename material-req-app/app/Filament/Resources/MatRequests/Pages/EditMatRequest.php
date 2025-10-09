<?php

namespace App\Filament\Resources\MatRequests\Pages;

use App\Filament\Resources\MatRequests\MatRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMatRequest extends EditRecord
{
    protected static string $resource = MatRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    protected function afterSave(): void
    {
        $userId = filament()->auth()->user()->id;

        // Update last_edited_by
        $this->record->updateQuietly([
            'last_edited_by' => $userId,
        ]);

        $approval = $this->record->approvals()->latest('approved_at')->first();

        if($approval->status === 'Rejected' && $this->record->wasChanged())
        {
            $approval->update([
                'status' => 'Revision',
            ]);
        }
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
