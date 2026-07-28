<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reference',
        'price',
        'image_path',
        'image',
        'pcs_cts',
        'unit_cbm',
        'unit_gw',
        'unit_nw',
        'description',
    ];

    /**
     * Suppliers that provide this product
     */
    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class)
            ->withPivot([
                'buying_price',
                'payment_status',
                'payment_method',
                'date_first_payment',
                'date_rest_payment',
                'discount',
            ])
            ->withTimestamps();
    }

    /**
     * Clients that buy this product
     */
    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class)
            ->withPivot(['price'])
            ->withTimestamps();
    }

    /**
     * Invoice items for this product
     */
    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Packing lists relation
     */
    public function packingLists(): BelongsToMany
    {
        return $this->belongsToMany(PackingList::class)
            ->withPivot('ctn')
            ->withTimestamps();
    }

    /**
     * Proforma invoices relation
     */
    public function proformaInvoices()
    {
        return $this->belongsToMany(
            ProformaInvoice::class,
            'proforma_invoice_product'
        )
        ->withPivot([
            'ctn',
            'unit_price'
        ]);
    }

    /**
     * Commercial invoices relation
     */
    public function commercialInvoices()
    {
        return $this->belongsToMany(
            CommercialInvoice::class,
            'commercial_invoice_product'
        );
    }

    public function invoices()
    {
        return $this->belongsToMany(
            Invoice::class,
            'invoice_items'
        );
    }

    public function proformaClients()
    {
        return Client::whereHas('proformaInvoices.products', function ($query) {

            $query->where('products.id', $this->id);

        });
    }
}