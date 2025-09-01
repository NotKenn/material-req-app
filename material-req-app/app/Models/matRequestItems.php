<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class matRequestItems extends Model
{
    protected $fillables = [
        //id, kodeRequest(FK), itemName, Qty, Price, total
        'kodeMR',
        'itemName',
        'Qty',
        'Price',
        'total'
    ];
    
    public $timestamps = false;
}
