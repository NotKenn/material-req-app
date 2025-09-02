<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class vendor extends Model
{
    public $table = 'vendor';

    protected $fillable = [
        //id, vendorName, alamat, nomor telepon
        'vendorName',
        'alamat',
        'namaKontak',
        'nomorTelepon'
    ];
    public $timestamps = false;
}
