<?php

namespace App\Filament\Resources\MatRequests\Pages;

use App\Filament\Resources\MatRequests\MatRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMatRequest extends CreateRecord
{
    protected static string $resource = MatRequestResource::class;

    protected static ?string $title = 'Create Material Request';
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function afterCreate(): void
    {
        // akses record yang baru dibuat
        $this->record->user_id = filament()->auth()->user()->id;
        $this->record->save();
    }
}
