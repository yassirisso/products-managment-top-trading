<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Client extends Model
{
    protected $fillable = ['name'];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function purchasedProducts()
    {
        return $this->hasManyThrough(
            Product::class,
            InvoiceItem::class,
            'invoice_id', // Foreign key on invoice_items table
            'id',         // Local key on products table
            'id',         // Local key on clients table
            'product_id'  // Foreign key on invoice_items table
        )->through('invoices');
    }
}
