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

    public function mrItems()
    {
        return $this->hasMany(matRequestItems::class, 'mr_id');
    }

    public function mrDetails()
    {
        return $this->hasOne(mrDetails::class, 'mr_ids');
    }
    public function requester()
    {
        return $this->belongsTo(Requesters::class, 'requester_id');
    }
    
    public function pos()
    {
        return $this->belongsToMany(
            PoDetails::class,
            'po_mr',
            'mr_id',
            'po_id'
        );
    }
}
