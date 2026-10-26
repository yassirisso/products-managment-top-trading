<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingMark extends Model
{
    protected $fillable = [
        'name',
    ];

    public function warehouseFees()
    {
        return $this->hasMany(WarehouseFee::class);
    }
}