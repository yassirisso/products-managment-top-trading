<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = ['reference'];

    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class)
            ->withPivot('buying_price')
            ->withTimestamps();
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class)
            ->withPivot('price') // Only keep this if you need it
            ->withTimestamps();
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
