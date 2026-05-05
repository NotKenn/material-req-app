<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class department extends Model
{
    public $table = 'department';

    protected $fillable = [
        'departmentName',
    ];
    public $timestamps = false;
    public function mrRequests()
    {
        return $this->hasMany(matRequest::class);
    }
}
