<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class mrDetails extends Model
{
    protected $fillable = [
        'mr_ids',
        'tanggal',
        'tanggalPerlu',
        'lokasiPengantaran',
        'lampiran',
        'notes',

    ];

    protected $casts = [
    'lampiran' => 'array',
    ];

    public $timestamps = false;
}
