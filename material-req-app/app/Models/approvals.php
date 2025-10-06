<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class approvals extends Model
{
    public $table = 'approvals';

    protected $fillable = [
        'approvable_id',
        'approvable_type',
        'user_id',
        'status',
        // 'level',
        'approved_at',
    ];

    public $timestamps = false;
    
    public function approvable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
