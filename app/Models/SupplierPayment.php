<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierPayment extends Model
{

    protected $fillable = [

        'supplier_purchase_id',
        'amount',
        'currency',
        'payment_date',
        'note',

    ];


    public function purchase(): BelongsTo
    {
        return $this->belongsTo(
            SupplierPurchase::class,
            'supplier_purchase_id'
        );
    }

}