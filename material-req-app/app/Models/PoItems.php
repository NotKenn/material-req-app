<?php

namespace App\Models;

use App\Filament\Resources\MatRequests\Schemas\MRItemsForm;
use Illuminate\Database\Eloquent\Model;

class PoItems extends Model
{
    public $table = 'po_items';

    protected $fillable = [
        'po_id', //fk ke po_details.id
        'mr_item_id', //jaga-jaga biar gk ke overwrite 
        'itemName', //pake dari MR
        'qty', // ini juga
        'unit', // ini juga
        'price', //mulai dari sini kebawah itu dari PO, karena mereka yg cariin vendor
        'amount',
        'subtotal',
        'discount',
        'total'
    ];
    public $timestamps = false;
    
    protected $casts = [
    'price' => 'string',
    'discount' => 'string',
];

    public function po()
    {
        return $this->belongsTo(PoDetails::class, 'po_id');
    }

    public function mrItem()
    {
        return $this->belongsTo(matRequestItems::class, 'mr_item_id');
    }
}
