<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankAccount;

class BankAccountController extends Controller
{
    public function index()
    {
        $bankAccounts =
            auth()->user()
                ->bankAccounts()
                ->latest()
                ->get();

        return view(
            'bank-accounts.index',
            compact('bankAccounts')
        );
    }

    public function create()
    {
        return view('bank-accounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'beneficiary_name' => 'required|string',

            'account_number' => 'required|string',

            'swift' => 'required|string',

            'bank_name' => 'required|string',

            'bank_address' => 'required|string',
        ]);

        BankAccount::create([

            'user_id' => auth()->id(),

            'beneficiary_name' => $request->beneficiary_name,

            'account_number' => $request->account_number,

            'swift' => $request->swift,

            'bank_name' => $request->bank_name,

            'bank_address' => $request->bank_address,
        ]);

        return redirect()
            ->route('bank-accounts.index')
            ->with(
                'success',
                'Bank Account Added Successfully'
            );
    }
}