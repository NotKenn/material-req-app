<?php

namespace App\Filament\Resources\PoDetails\Pages;

use App\Filament\Resources\PoDetails\PoDetailsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPoDetails extends ListRecords
{
    protected static string $resource = PoDetailsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
