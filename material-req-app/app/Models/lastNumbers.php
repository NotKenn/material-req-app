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
        'lastNumber',
    ];
    
    public static function generate(string $type): int
    {
        return DB::transaction(function () use ($type) {
            $record = static::lockForUpdate()->firstOrCreate(
                ['type' => $type],
                ['last_number' => 0]
            );

            $record->last_number++;
            $record->save();

            return $record->last_number;
        });
    }
}
