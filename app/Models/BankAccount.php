<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [

        'user_id',

        'beneficiary_name',

        'account_number',

        'swift',

        'bank_name',

        'bank_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}