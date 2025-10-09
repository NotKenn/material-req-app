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
        'po_file',
        'user_id',
        'address',
        'name',
        'phone',
        'departemen',
        'edit_count',
        'last_edited_by'
    ];
    
    public $timestamps = false;

    protected static function booted()
    {
        static::updating(function ($model) {
            $model->edit_count = $model->edit_count + 1;
        });
    }

    public function latestApproval()
    {
        return $this->hasOne(approvals::class, 'approvable_id')
                    ->where('approvable_type', self::class)
                    ->latestOfMany();
    }
    public function editor()
    {
        return $this->belongsTo(User::class, 'last_edited_by');
    }
    public function approvals()
    {
        return $this->morphMany(approvals::class, 'approvable');
    }

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
