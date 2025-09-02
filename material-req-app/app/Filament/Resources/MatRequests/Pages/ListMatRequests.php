<?php

namespace App\Filament\Resources\MatRequests\Pages;

use App\Filament\Resources\MatRequests\MatRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMatRequests extends ListRecords
{
    protected static string $resource = MatRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
