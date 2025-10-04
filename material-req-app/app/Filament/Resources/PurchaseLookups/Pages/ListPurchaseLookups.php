<?php

namespace App\Filament\Resources\PurchaseLookups\Pages;

use App\Filament\Resources\PurchaseLookups\PurchaseLookupResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPurchaseLookups extends ListRecords
{
    protected static string $resource = PurchaseLookupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
