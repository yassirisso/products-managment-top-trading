<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingCompany extends Model
{
    protected $fillable = [
        'name',
        'tel',
        'email',
    ];

    public function contacts(): HasMany
    {
        return $this->hasMany(ShippingCompanyContact::class);
    }

    public function fees(): HasMany
    {
        return $this->hasMany(ShippingFee::class);
    }
}