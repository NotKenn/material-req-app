<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class po_details extends Model
{
    protected $fillable = [
        'companyName',
        'officeAdress',
        'contactName',
        'phone',
        'po_number',
        'date',
        // 'mrsrNumber', //dari po_mr table, bagian mr_id where po_id = @po_id
        'termOfPayment',
        'vendorID'
    ];

    public $timestamps = false;
}
