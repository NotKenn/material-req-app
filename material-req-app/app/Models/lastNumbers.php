<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class lastNumbers extends Model
{
    public $table = 'lastNumbers';

    protected $fillable = [
        // id, kodeRequest, status, po_file, created_at
        'type',
        'lastNumbers',
    ];

    public static function generate(string $type)
    {
        return DB::transaction(function () use ($type) {
            $current_period = now()->format('Y-m');

            $record = static::lockForUpdate()->firstOrCreate(
                ['type' => $type],
                ['lastNumbers' => 0]
            );
            if($record->last_reset_period !== $current_period){
                $record->lastNumbers = 1;
                $record->last_reset_period = $current_period;
            }else
            {
                $record->lastNumbers++;
            }
            $record->save();

            $getMonth= now()->month;
            $getYear = now()->year;

            $finalCode = "$record->lastNumbers"."/"."$record->type"."/"."$getMonth"."/"."$getYear";
            return $finalCode;
        });
    }
    public static function peek(string $type)
    {
        $current_period = now()->format('Y-m');

        $record = static::firstOrCreate(
            ['type' => $type],
            ['lastNumbers' => 0]
        );

        $getMonth= now()->month;
        $getYear = now()->year;
        $nextNumber = $record->last_reset_period !== $current_period
        ? 1
        : $record->lastNumbers + 1;
        $finalCode = "$nextNumber"."/"."$record->type"."/"."$getMonth"."/"."$getYear";

        return $finalCode; // cuma lihat next number, tidak save
    }
}

