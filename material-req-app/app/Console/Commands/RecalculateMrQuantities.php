<?php

namespace App\Console\Commands;

use App\Models\matRequest;
use App\Models\matRequestItems;
use Illuminate\Console\Command;

class RecalculateMrQuantities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:recalculate-mr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate fulfillment status for all Material Requests';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $mrs = matRequest::all();
        $updatedCount = 0;

        foreach ($mrs as $mr) {
            $items = matRequestItems::where('mr_id', $mr->id)->get();

            if ($items->isEmpty()) continue;

            $allFulfilled = $items->every(fn($i) => $i->remainingQty <= 0);

            $mr->isFulfilled = $allFulfilled;
            $mr->status = $allFulfilled ? 'Closed' : 'Open';
            $mr->save();

            $updatedCount++;
        }

        $this->info("Recalculated fulfillment for {$updatedCount} MR(s).");
    }
}
