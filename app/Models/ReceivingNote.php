<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceivingNote extends Model
{
    protected $fillable = [
        'proforma_invoice_id',
        'date',
        'container_no',
        'seal_no',
        'note',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function proformaInvoice()
    {
        return $this->belongsTo(ProformaInvoice::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'receiving_note_product')
            ->withPivot(['ctn', 'note', 'id'])
            ->withTimestamps();
    }
}
