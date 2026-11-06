<?php

namespace App\Http\Controllers;

use App\Models\ReceivingNote;
use App\Models\SupplierReceivingPayment;
use Illuminate\Http\Request;

class SupplierReceivingPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = SupplierReceivingPayment::with('receivingNote.proformaInvoice.client');

        if ($request->search) {
            $query->where('note', 'like', '%' . $request->search . '%');
        }

        $payments = $query->latest('payment_date')->paginate(10)->withQueryString();

        return view('supplier-receiving-payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $receivingNotes = ReceivingNote::with('proformaInvoice.client')->latest()->get();

        $lockedNote = null;
        if ($request->filled('receiving_note_id')) {
            $lockedNote = ReceivingNote::with('proformaInvoice.client')->find($request->receiving_note_id);
        }

        return view('supplier-receiving-payments.create', compact('receivingNotes', 'lockedNote'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiving_note_id' => 'required|exists:receiving_notes,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|in:RMB,USD',
            'payment_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        SupplierReceivingPayment::create($validated);

        return redirect()->route('supplier-receiving-payments.index')
            ->with('success', __('Payment recorded successfully'));
    }

    public function edit(SupplierReceivingPayment $supplierReceivingPayment)
    {
        $receivingNotes = ReceivingNote::with('proformaInvoice.client')->latest()->get();
        return view('supplier-receiving-payments.edit', compact('supplierReceivingPayment', 'receivingNotes'));
    }

    public function update(Request $request, SupplierReceivingPayment $supplierReceivingPayment)
    {
        $validated = $request->validate([
            'receiving_note_id' => 'required|exists:receiving_notes,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|in:RMB,USD',
            'payment_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        $supplierReceivingPayment->update($validated);

        return redirect()->route('supplier-receiving-payments.index')
            ->with('success', __('Payment updated successfully'));
    }

    public function destroy(SupplierReceivingPayment $supplierReceivingPayment)
    {
        $supplierReceivingPayment->delete();

        return redirect()->route('supplier-receiving-payments.index')
            ->with('success', __('Payment deleted successfully'));
    }
}
