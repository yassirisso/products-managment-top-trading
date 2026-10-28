<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankAccount;

class BankAccountController extends Controller
{
    public function index(Request $request)
    {
        $query = BankAccount::query();

        if ($request->search) {

            $query->where(function ($q) use ($request) {

                $q->where('bank_name', 'like', '%' . $request->search . '%')
                    ->orWhere('beneficiary_name', 'like', '%' . $request->search . '%')
                    ->orWhere('account_number', 'like', '%' . $request->search . '%')
                    ->orWhere('swift', 'like', '%' . $request->search . '%');
            });
        }

        $bankAccounts = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

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

    public function edit(BankAccount $bankAccount)
    {
        return view('bank-accounts.edit', compact('bankAccount'));
    }

    public function update(Request $request, BankAccount $bankAccount)
    {
        $validated = $request->validate([
            'beneficiary_name' => 'required|string',
            'account_number' => 'required|string',
            'swift' => 'required|string',
            'bank_name' => 'required|string',
            'bank_address' => 'required|string',
        ]);

        $bankAccount->update($validated);

        return redirect()
            ->route('bank-accounts.index')
            ->with('success', 'Bank Account Updated Successfully');
    }
}
