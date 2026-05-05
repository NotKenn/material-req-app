<?php

namespace App\Services;

use App\Models\PoDetails;
use Illuminate\Support\Facades\DB;

class PORevisionService
{
    public function revise(PoDetails $po)
        {
            return DB::transaction(function () use ($po) {

                // 1. Ambil PO aktif (safety, optional nanti kita refine)
                $currentPo = $po;

                // 2. Set PO lama jadi tidak aktif
                $currentPo->is_active = false;
                $currentPo->save();

                // 3. Clone PO (tanpa ID)
                $newPo = $currentPo->replicate();

                // 4. Increment revision
                $newPo->revision = $currentPo->revision + 1;

                // 5. Set sebagai active
                $newPo->is_active = true;

                // 6. Save PO baru
                $newPo->save();

                // 7. Clone items (nanti kita isi)
                // TODO: clone po_items ke $newPo

                return $newPo;
            });
        }
}

