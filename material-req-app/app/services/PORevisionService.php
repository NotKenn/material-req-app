<?php

namespace App\Services;

use App\Models\PoDetails;
use App\Observers\PoItemObserver;
use Illuminate\Support\Facades\DB;

class PORevisionService
{
    public function revise(PoDetails $po)
        {
            return DB::transaction(function () use ($po) {

                // 1. Ambil PO aktif (safety, optional nanti kita refine)
                $currentPo = $po;

                // 2. Set PO lama jadi tidak aktif
                $currentPo->isActive = false;
                $currentPo->save();

                // 3. Clone PO (tanpa ID)
                $newPo = $currentPo->replicate();

                // 4. Increment revision
                $newPo->revision = $currentPo->revision + 1;

                // 5. Set sebagai active + user_id
                $newPo->isActive = true;
                $newPo->user_id = filament()->auth()->id();

                // 6. Save PO baru
                $newPo->save();
                $newPo->matRequests()->sync(
                    $po->matRequests->pluck('id')->toArray()
                );

                // 7. Clone items (nanti kita isi)
                foreach ($po->items as $item) {
                    $newItem = $item->replicate();

                    $newItem->po_id = $newPo->id;
                    $newItem->save();
                }

                // FINAL RECALC
                foreach ($newPo->items as $item) {
                    PoItemObserver::updateRemaining($item->mr_item_id);
                }

                return $newPo;
            });
        }
}

