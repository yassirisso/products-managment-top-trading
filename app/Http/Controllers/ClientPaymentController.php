<?php

namespace App\Http\Controllers;

use App\Models\ClientPayment;
use App\Models\ProformaInvoice;
use Illuminate\Http\Request;

class ClientPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = ClientPayment::with('proformaInvoice.client');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('proformaInvoice.client', function ($c) use ($request) {
                    $c->where('name', 'like', '%' . $request->search . '%');
                })->orWhere('note', 'like', '%' . $request->search . '%');
            });
        }

        $payments = $query->latest('payment_date')->paginate(10)->withQueryString();

        return view('client-payments.index', compact('payments'));
    }

    public function create()
    {
        $proformaInvoices = ProformaInvoice::with('client')->latest()->get();
        return view('client-payments.create', compact('proformaInvoices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'proforma_invoice_id' => 'required|exists:proforma_invoices,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|in:RMB,USD',
            'payment_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        ClientPayment::create($validated);

        return redirect()->route('client-payments.index')
            ->with('success', __('Payment recorded successfully'));
    }

    public function edit(ClientPayment $clientPayment)
    {
        $proformaInvoices = ProformaInvoice::with('client')->latest()->get();
        return view('client-payments.edit', compact('clientPayment', 'proformaInvoices'));
    }

    public function update(Request $request, ClientPayment $clientPayment)
    {
        $validated = $request->validate([
            'proforma_invoice_id' => 'required|exists:proforma_invoices,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|in:RMB,USD',
            'payment_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        $clientPayment->update($validated);

        return redirect()->route('client-payments.index')
            ->with('success', __('Payment updated successfully'));
    }

    public function destroy(ClientPayment $clientPayment)
    {
        $clientPayment->delete();

        return redirect()->route('client-payments.index')
            ->with('success', __('Payment deleted successfully'));
    }
}
