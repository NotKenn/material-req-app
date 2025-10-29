<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class penerima extends Model
{
    public $table ='penerima';

    protected $fillable = [
        'namaPenerima',
        'nomorKontak',
        'lokasiPengantaran',

    ];

    public $timestamps = false;
}
