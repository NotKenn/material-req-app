<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class matRequestItems extends Model
{
    public $table = 'mr_items';

    protected $fillable = [
        //id, kodeRequest(FK), itemName, Qty, Price, total
        'mr_id',
        'itemName',
        'Qty',
        'satuan',
        'notes'
    ];

    public $timestamps = false;

    public function itemMaster()
    {
        return $this->belongsTo(itemmaster::class, 'itemName');
    }
}
