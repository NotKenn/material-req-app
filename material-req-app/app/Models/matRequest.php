<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class matRequest extends Model
{
    public $table = 'mr_table';

    protected $fillable = [
        // id, kodeRequest, status, po_file, created_at
        'kodeRequest',
        'requester_id',
        'created_at',
        'status',
        'po_file'
    ];
    
    public $timestamps = false;
}
