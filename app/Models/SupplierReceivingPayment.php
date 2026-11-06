<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierReceivingPayment extends Model
{
    protected $fillable = [
        'receiving_note_id',
        'amount',
        'currency',
        'payment_date',
        'note',
        'supplier_id',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function receivingNote(): BelongsTo
    {
        return $this->belongsTo(ReceivingNote::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
