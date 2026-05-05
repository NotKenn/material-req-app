<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class company extends Model
{
    public $table = 'company';

    protected $fillable = [
        'companyName'
    ];
    public $timestamps = false;
}
