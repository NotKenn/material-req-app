<?php

namespace App\Filament\Resources\PurchaseLookups\Pages;

use App\Filament\Resources\PurchaseLookups\PurchaseLookupResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPurchaseLookup extends EditRecord
{
    protected static string $resource = PurchaseLookupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }  
}
