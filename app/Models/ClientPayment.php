<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientPayment extends Model
{
    protected $fillable = [
        'proforma_invoice_id',
        'amount',
        'currency',
        'exchange_rate',
        'amount_rmb',
        'payment_date',
        'note',
        'screenshot',
    ];

    protected $casts = [
        'payment_date'  => 'date',
        'amount'        => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'amount_rmb'    => 'decimal:2',
    ];

    public function proformaInvoice(): BelongsTo
    {
        return $this->belongsTo(ProformaInvoice::class);
    }

    /**
     * RMB equivalent of the payment.
     *  - USD: amount / exchange_rate   (e.g. 300 / 6.6 = 45.45)
     *  - RMB: the amount itself
     */
    public function getAmountInRmbAttribute(): ?float
    {
        if ($this->currency === 'USD') {
            if (!$this->exchange_rate || $this->exchange_rate <= 0) {
                return null;
            }
            return round((float) $this->amount / (float) $this->exchange_rate, 2);
        }

        return (float) $this->amount;
    }

    /**
     * Display string used in the index table.
     *   USD -> "300.00 USD (45.45 RMB)"
     *   RMB -> "600.00 RMB"
     */
    public function getFormattedAmountAttribute(): string
    {
        if ($this->currency === 'USD') {
            $rmb = $this->amount_in_rmb;

            return number_format((float) $this->amount, 2) . ' USD'
                . ($rmb !== null ? ' (' . number_format($rmb, 2) . ' RMB)' : '');
        }

        return number_format((float) $this->amount, 2) . ' RMB';
    }
}
