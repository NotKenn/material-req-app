<?php

namespace App\Filament\Resources\PoDetails\Pages;

use App\Filament\Resources\PoDetails\PoDetailsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPoDetails extends EditRecord
{
    protected static string $resource = PoDetailsResource::class;

    protected static ?string $title = 'Edit Purchase Orders';
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    // di Pages/CreatePoDetails.php & EditPoDetails.php
protected function afterSave(): void
{
    $po = $this->record;

    // Sync matRequests pivot (po_mr)
    $matRequests = $this->form->getState()['matRequests'] ?? [];
    $po->matRequests()->sync($matRequests);

    // Optional: update PO items dari MR items
    $items = [];
    foreach ($matRequests as $mrId) {
        $mrItems = \App\Models\MatRequestItems::where('mr_id', $mrId)->get();
        foreach ($mrItems as $item) {
            $items[] = [
                'mr_item_id' => $item->id,
                'itemName' => $item->itemName,
                'qty' => $item->Qty,
                'unit' => $item->satuan ?? $item->uom ?? '',
            ];
        }
    }

    $po->items()->delete(); // hapus dulu items lama
    $po->items()->createMany($items); // insert items baru
}


    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
