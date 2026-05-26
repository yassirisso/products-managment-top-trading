<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = ['reference','price', 'image_path', 'image','pcs_cts','unit_cbm','unit_gw','unit_nw','description',];

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

    public function packingLists()
    {
        return $this->belongsToMany(PackingList::class)
            ->withPivot('ctn')
            ->withTimestamps();
    }

    public function proformaInvoices()
    {
        return $this->belongsToMany(
            ProformaInvoice::class,
            'proforma_invoice_product'
        )
        ->withPivot('ctn', 'unit_price')
        ->withTimestamps();
    }

    public function commercialInvoices()
    {
        return $this->belongsToMany(CommercialInvoice::class)
                    ->withPivot([
                        'ctn',
                        'unit_price'
                    ])
                    ->withTimestamps();
    }
}
