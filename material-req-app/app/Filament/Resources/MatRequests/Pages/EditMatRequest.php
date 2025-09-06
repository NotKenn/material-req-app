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
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
