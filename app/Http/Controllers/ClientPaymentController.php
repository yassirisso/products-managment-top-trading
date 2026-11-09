<?php

namespace App\Http\Controllers;

use App\Models\ClientPayment;
use App\Models\ProformaInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function create(Request $request)
    {
        $proformaInvoices = ProformaInvoice::with('client')->latest()->get();

        $lockedInvoice = null;
        if ($request->filled('proforma_invoice_id')) {
            $lockedInvoice = ProformaInvoice::with('client')->find($request->proforma_invoice_id);
        }

        return view('client-payments.create', compact('proformaInvoices', 'lockedInvoice'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'proforma_invoice_id' => 'required|exists:proforma_invoices,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|in:RMB,USD',
            'exchange_rate' => 'nullable|required_if:currency,USD|numeric|gt:0',
            'payment_date' => 'required|date',
            'note' => 'nullable|string',
            'screenshot' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $validated = $this->applyCurrencyConversion($validated);

        if ($request->hasFile('screenshot')) {
            $validated['screenshot'] = $request->file('screenshot')->store('payments', 'public');
        }

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
            'exchange_rate' => 'nullable|required_if:currency,USD|numeric|gt:0',
            'payment_date' => 'required|date',
            'note' => 'nullable|string',
            'screenshot' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $validated = $this->applyCurrencyConversion($validated);

        if ($request->hasFile('screenshot')) {
            if ($clientPayment->screenshot) {
                Storage::disk('public')->delete($clientPayment->screenshot);
            }
            $validated['screenshot'] = $request->file('screenshot')->store('payments', 'public');
        }

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

    /**
     * Normalise the currency fields before saving.
     *  - USD: store exchange_rate and amount_rmb = amount / exchange_rate
     *  - RMB: null out exchange_rate and amount_rmb = amount
     */
    private function applyCurrencyConversion(array $data): array
    {
        if (($data['currency'] ?? null) === 'USD') {
            $rate = (float) ($data['exchange_rate'] ?? 0);
            $data['amount_rmb'] = $rate > 0
                ? round($data['amount'] / $rate, 2)
                : null;
        } else {
            $data['exchange_rate'] = null;
            $data['amount_rmb'] = $data['amount'];
        }

        return $data;
    }
}
