<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceivingNote extends Model
{
    protected $fillable = [
        'packing_list_id',
        'date',
        'container_no',
        'seal_no',
        'note',
        'total_amount',
    ];

    protected $casts = [
        'date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function packingList()
    {
        return $this->belongsTo(PackingList::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'receiving_note_product')
            ->withPivot(['ctn', 'note', 'id'])
            ->withTimestamps();
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\SupplierReceivingPayment::class);
    }
}
