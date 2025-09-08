<?php

namespace App\Filament\Resources\PoDetails\Pages;

use App\Filament\Resources\PoDetails\PoDetailsResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePoDetails extends CreateRecord
{
    protected static string $resource = PoDetailsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    // protected function afterSave(): void
    // {
    //     $po = $this->record;

    //     // ✅ selalu sync MR yg dipilih
    //     $matRequests = $this->form->getState()['matRequests'] ?? [];

    //     if (!empty($matRequests)) {
    //         $po->matRequests()->sync($matRequests);

    //         $items = [];
    //         foreach ($matRequests as $mrId) {
    //             $mrItems = \App\Models\MatRequestItems::where('mr_id', $mrId)->get();
    //             foreach ($mrItems as $item) {
    //                 $items[] = [
    //                     'mr_item_id' => $item->id,
    //                     'itemName'   => $item->itemName,
    //                     'qty'        => $item->Qty,
    //                     'unit'       => $item->satuan ?? $item->uom ?? '',
    //                 ];
    //             }
    //         }

    //         // regenerate hanya kalau ada data
    //         if (!empty($items)) {
    //             $po->items()->delete();
    //             $po->items()->createMany($items);
    //         }
    //     }
    // }
}
