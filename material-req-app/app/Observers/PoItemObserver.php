<?php

namespace App\Observers;

use App\Models\PoItems;
use App\Models\matRequestItems;

class PoItemObserver
{
    public function saved(PoItems $item)
    {
        self::updateRemaining($item->mr_item_id);
    }

    public function deleted(PoItems $item)
    {
        self::updateRemaining($item->mr_item_id);
    }

    private static function updateRemaining($mrItemId)
    {
        if (!$mrItemId) return;

        $mrItem = matRequestItems::find($mrItemId);
        if (!$mrItem) return;

        $totalUsed = PoItems::where('mr_item_id', $mrItemId)
            ->whereHas('po', function ($q) {
                $q->where('isActive', true);
            })
            ->sum('qty');

        $remaining = max(0, (float)$mrItem->Qty - (float)$totalUsed);

        $mrItem->remainingQty = $remaining;

        $mrItem->saveQuietly(); // biar gak trigger observer lain
    }
}
