<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class requesters extends Model
{
    public $table = 'requesters';

    protected $fillable = [
        //id, vendorName, alamat, nomor telepon
        'namaPT',
        'alamatPT',
        'namaKontakPT',
        'noTelpKontakPT'
    ];
    public $timestamps = false;
}
