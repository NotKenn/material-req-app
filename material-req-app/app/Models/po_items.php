<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class po_items extends Model
{
    protected $fillable = [
        'po_id',
        'mr_item_id', //jaga-jaga biar gk ke overwrite 
        'itemName', //pake dari MR
        'qty', // ini juga
        'unit', // ini juga
        'price', //mulai dari sini kebawah itu dari PO, karena mereka yg cariin vendor
        'amount',
        'subtotal',
        'discount',
        'total'
    ];
    public $timestamps = false;
}
