<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackingList extends Model
{
    protected $fillable = [
        'client_id',
        'company_name',
        'company_address',
        'company_tel',
        'title',
        'port_of_loading',
        'port_of_discharge',
        'date',
        'container_no',
        'seal_no',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('ctn')
            ->withTimestamps();
    }
}