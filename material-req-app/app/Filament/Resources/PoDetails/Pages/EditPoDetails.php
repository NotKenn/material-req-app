<?php

namespace App\Filament\Resources\PoDetails\Pages;

use App\Filament\Resources\PoDetails\PoDetailsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPoDetails extends EditRecord
{
    protected static string $resource = PoDetailsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    protected function afterSave(): void
    {
        $approval = $this->record->approvals()->latest('approved_at')?->first();

        if($approval?->status === 'Rejected' && $this->record->wasChanged())
        {
            $approval?->update([
                'status' => 'Revision',
            ]);
        }
    }

    // protected function afterSave(): void
    // {
    //     $po = $this->record;

    //     $matRequests = $this->form->getState()['matRequests'] ?? [];

    //     // ✅ hanya sync kalau user memang ada pilihan MR
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

    //         if (!empty($items)) {
    //             $po->items()->delete();
    //             $po->items()->createMany($items);
    //         }
    //     }
        // kalau kosong → jangan sentuh relasi, biar nggak ke-wipe
    // }
}
