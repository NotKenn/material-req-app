<?php

namespace App\Observers;

use App\Models\matRequestItems;
use App\Models\matRequest;

class MrItemObserver
{
    public function saved(matRequestItems $item)
    {
        $this->checkMrFulfillment($item->mr_id);
    }

    public function deleted(matRequestItems $item)
    {
        $this->checkMrFulfillment($item->mr_id);
    }

    private function checkMrFulfillment($mrId)
    {
        $items = matRequestItems::where('mr_id', $mrId)->get();

        if ($items->isEmpty()) {
            // Kalau semua item dihapus, set MR jadi "Open" dan non-fulfilled
            $this->updateMr($mrId, false);
            return;
        }

        // Cek apakah semua item sudah fulfilled
        $allFulfilled = $items->every(fn ($i) => isset($i->remainingQty) && $i->remainingQty <= 0);

        $this->updateMr($mrId, $allFulfilled);
    }

    private function updateMr($mrId, $fulfilled)
    {
        $mr = matRequest::find($mrId);
        if (!$mr) return;

        $mr->isFulfilled = $fulfilled;
        $mr->status = $fulfilled ? 'Closed' : 'Open';
        $mr->saveQuietly(); // pakai saveQuietly biar gak trigger observer MR-nya sendiri
    }
}
