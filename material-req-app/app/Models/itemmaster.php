<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class itemmaster extends Model
{
    public $table = 'item_master';

    protected $fillable = [
        'itemName',
        'itemDesc',
    ];
    public $timestamps = false;

}
