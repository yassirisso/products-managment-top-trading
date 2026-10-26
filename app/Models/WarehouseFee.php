<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseFee extends Model
{
    protected $fillable = [
        'container_no',
        'date',
        'client_id',
        'shipping_mark_id',
        'montant_rmb',
    ];

    protected $casts = [
        'date' => 'date',
        'montant_rmb' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function shippingMark()
    {
        return $this->belongsTo(ShippingMark::class);
    }
}