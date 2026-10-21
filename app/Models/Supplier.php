<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'shop_number',
        'email',
        'phone',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot([
                'buying_price',
                'payment_status',
                'payment_method',
                'date_first_payment',
                'date_rest_payment',
                'discount'
            ]);
    }

    public function clients()
    {
        return Client::whereHas('products.suppliers', function ($query) {
            $query->where('suppliers.id', $this->id);
        })->distinct();
    }

    public function purchases()
    {
        return $this->hasMany(SupplierPurchase::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(SupplierContact::class);
    }
}
