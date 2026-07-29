<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommercialInvoice extends Model
{
    protected $fillable = [

        'client_id',
        'date',
        'invoice_no',
        'port_of_loading',
        'port_of_discharge',
        'mode_of_delivery',
        'country_of_origin',
        'bank_account_id',
        'currency',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot([
                'ctn',
                'unit_price'
            ])
            ->withTimestamps();
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }
}