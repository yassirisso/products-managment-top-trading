<?php

namespace App\Http\Controllers;

use App\Models\ReceivingNote;
use App\Models\SupplierReceivingPayment;
use Illuminate\Http\Request;

class SupplierReceivingPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = SupplierReceivingPayment::with(['receivingNote.packingList.client', 'supplier']);

        if ($request->search) {
            $query->where('note', 'like', '%' . $request->search . '%');
        }

        $payments = $query->latest('payment_date')->paginate(10)->withQueryString();

        return view('supplier-receiving-payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $preselectedSupplierId = $request->query('supplier_id');

        // If a supplier is preselected, only show notes that contain this supplier's products
        $notesQuery = ReceivingNote::with(['packingList.client', 'products.suppliers'])->latest();

        if ($preselectedSupplierId) {
            $notesQuery->whereHas('products.suppliers', function ($q) use ($preselectedSupplierId) {
                $q->where('suppliers.id', $preselectedSupplierId);
            });
        }

        $receivingNotes = $notesQuery->get();

        $suppliers = \App\Models\Supplier::orderBy('name')->get();

        $lockedNote = null;
        if ($request->filled('receiving_note_id')) {
            $lockedNote = ReceivingNote::with(['packingList.client', 'products.suppliers'])->find($request->receiving_note_id);
        }

        return view('supplier-receiving-payments.create', compact(
            'receivingNotes',
            'suppliers',
            'lockedNote',
            'preselectedSupplierId'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiving_note_id' => 'required|exists:receiving_notes,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|in:RMB,USD',
            'payment_date' => 'required|date',
            'note' => 'nullable|string',
            'supplier_id' => 'required|exists:suppliers,id',
        ]);

        SupplierReceivingPayment::create($validated);

        return redirect()->route('supplier-receiving-payments.index')
            ->with('success', __('Payment recorded successfully'));
    }

    public function edit(SupplierReceivingPayment $supplierReceivingPayment)
    {
        $receivingNotes = ReceivingNote::with(['packingList.client', 'products.suppliers'])->latest()->get();
        $suppliers = \App\Models\Supplier::orderBy('name')->get();

        return view('supplier-receiving-payments.edit', compact('supplierReceivingPayment', 'receivingNotes', 'suppliers'));
    }

    public function update(Request $request, SupplierReceivingPayment $supplierReceivingPayment)
    {
        $validated = $request->validate([
            'receiving_note_id' => 'required|exists:receiving_notes,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|in:RMB,USD',
            'payment_date' => 'required|date',
            'note' => 'nullable|string',
            'supplier_id' => 'required|exists:suppliers,id',
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
