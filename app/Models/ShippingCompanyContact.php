<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingCompanyContact extends Model
{
    protected $fillable = [
        'shipping_company_id',
        'name',
        'phone',
        'email',
        'position',
    ];

    public function shippingCompany(): BelongsTo
    {
        return $this->belongsTo(ShippingCompany::class);
    }
}