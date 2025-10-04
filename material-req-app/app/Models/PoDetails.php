<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\vendor;

class PoDetails extends Model
{
    public $table = 'po_details';

    protected $fillable = [
        'companyName',
        'officeAddress',
        'contactName',
        'phone',
        'po_number',
        'date',
        // 'mrsrNumber', //dari po_mr table, bagian mr_id where po_id = @po_id
        'termOfPayment',
        'vendorID',
        'isRevised',
        'gl_disc'
    ];

    public $timestamps = false;

    public function vendor()
    {
        return $this->belongsTo(vendor::class, 'vendorID', 'id');
    }
    public function approvals()
    {
        return $this->morphMany(approvals::class, 'approvable');
    }
    public function matRequests()
    {
        return $this->belongsToMany(
            MatRequest::class, // model Material Request kamu
            'po_mr',           // nama tabel pivot
            'po_id',           // FK di pivot ke PO
            'mr_id'            // FK di pivot ke Material Request
        );
    }
    public function items()
    {
        return $this->hasMany(PoItems::class, 'po_id');
    }

}
