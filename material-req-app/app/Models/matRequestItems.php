<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class matRequestItems extends Model
{
    protected $fillable = [
        //id, kodeRequest(FK), itemName, Qty, Price, total
        'mr_id',
        'itemName',
        'Qty',
        'satuan'
    ];
    
    public $timestamps = false;
}
