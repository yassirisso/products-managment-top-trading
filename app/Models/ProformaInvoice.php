<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProformaInvoice extends Model
{

    protected $fillable = [

        'client_id',

        'port_of_loading',
        'port_of_discharge',

        'date',

        'container_no',
        'seal_no',

        'local_charge',
        'currency',

    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'proforma_invoice_product'
        )
        ->withPivot('ctn', 'unit_price')
        ->withTimestamps();
    }
}
