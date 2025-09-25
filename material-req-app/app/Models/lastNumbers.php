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
    
    public static function generate(string $type): int
    {
        return DB::transaction(function () use ($type) {
            $record = static::lockForUpdate()->firstOrCreate(
                ['type' => $type],
                ['lastNumbers' => 0]
            );

            $record->lastNumbers++;
            $record->save();

            return $record->lastNumbers;
        });
    }
    public static function peek(string $type): int
    {
        $record = static::firstOrCreate(
            ['type' => $type],
            ['lastNumbers' => 0]
        );

        return $record->lastNumbers + 1; // cuma lihat next number, tidak save
    }
}
