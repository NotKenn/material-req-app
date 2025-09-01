<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class matRequest extends Model
{
    protected $fillable = [
        // id, kodeRequest, status, po_file, created_at
        'kodeRequest',
        'created_at',
        'status',
        'po_file'
    ];
    
    public $timestamps = false;
}
