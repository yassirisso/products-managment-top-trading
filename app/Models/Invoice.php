<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_date',
        'total_amount',
        'notes',
        'client_id', // Include if you're setting client_id via mass assignment
    ];

    protected $casts = [
        'invoice_date' => 'date', // or 'datetime' if you need time
    ];
    
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
