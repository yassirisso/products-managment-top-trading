<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'product_id',   // Required (from your error)
        'quantity',     // Required (since you're using it)
        'unit_price',   // Required (since you're using it)
        'total_price',  // Optional (if calculated in the model)
        'invoice_id',   // Required (foreign key)
    ];
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
