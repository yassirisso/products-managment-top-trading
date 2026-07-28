<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupplierPurchase extends Model
{

    protected $fillable = [

        'product_id',
        'supplier_id',
        'quantity',
        'unit_price',
        'total_amount',
        'currency',
        'payment_status',
        'purchase_date',
        'note',

    ];


    /**
     * Product purchased
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }


    /**
     * Supplier who sold the product
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }


    /**
     * Payments made for this purchase
     */
    public function payments(): HasMany
    {
        return $this->hasMany(SupplierPayment::class);
    }

}