<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProformaInvoice;
use App\Models\ReceivingNote;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReceivingNoteExport;

class ReceivingNoteController extends Controller
{
    public function index()
    {
        $receivingNotes = ReceivingNote::with('proformaInvoice')
            ->latest()
            ->paginate(10);

        return view('receiving-notes.index', compact('receivingNotes'));
    }

    public function create()
    {
        $proformaInvoices = ProformaInvoice::with('client')->latest()->get();
        $products = Product::with('suppliers')->get();

        return view('receiving-notes.create', compact('proformaInvoices', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'proforma_invoice_id' => 'nullable|exists:proforma_invoices,id',
            'date' => 'nullable|date',
            'container_no' => 'nullable|string|max:255',
            'seal_no' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'products' => 'nullable|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.ctn' => 'nullable|integer|min:0',
            'products.*.note' => 'nullable|string',
        ]);

        $receivingNote = ReceivingNote::create([
            'proforma_invoice_id' => $request->proforma_invoice_id,
            'date' => $request->date,
            'container_no' => $request->container_no,
            'seal_no' => $request->seal_no,
            'note' => $request->note,
        ]);

        if ($request->has('products')) {
            $syncData = [];
            foreach ($request->products as $productData) {
                if (!empty($productData['id'])) {
                    $syncData[$productData['id']] = [
                        'ctn' => $productData['ctn'] ?? 0,
                        'note' => $productData['note'] ?? null,
                    ];
                }
            }
            $receivingNote->products()->sync($syncData);
        }

        return redirect()
            ->route('receiving-notes.index')
            ->with('success', __('Receiving Note Created Successfully'));
    }

    public function show(ReceivingNote $receivingNote)
    {
        $receivingNote->load(['proformaInvoice', 'products.suppliers']);

        return view('receiving-notes.show', compact('receivingNote'));
    }

    public function edit(ReceivingNote $receivingNote)
    {
        $receivingNote->load('products');
        $proformaInvoices = ProformaInvoice::with('client')->latest()->get();
        $products = Product::with('suppliers')->get();

        return view('receiving-notes.edit', compact('receivingNote', 'proformaInvoices', 'products'));
    }

    public function update(Request $request, ReceivingNote $receivingNote)
    {
        $validated = $request->validate([
            'proforma_invoice_id' => 'nullable|exists:proforma_invoices,id',
            'date' => 'nullable|date',
            'container_no' => 'nullable|string|max:255',
            'seal_no' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'products' => 'nullable|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.ctn' => 'nullable|integer|min:0',
            'products.*.note' => 'nullable|string',
        ]);

        $receivingNote->update([
            'proforma_invoice_id' => $request->proforma_invoice_id,
            'date' => $request->date,
            'container_no' => $request->container_no,
            'seal_no' => $request->seal_no,
            'note' => $request->note,
        ]);

        $syncData = [];
        if ($request->has('products')) {
            foreach ($request->products as $productData) {
                if (!empty($productData['id'])) {
                    $syncData[$productData['id']] = [
                        'ctn' => $productData['ctn'] ?? 0,
                        'note' => $productData['note'] ?? null,
                    ];
                }
            }
        }
        $receivingNote->products()->sync($syncData);

        return redirect()
            ->route('receiving-notes.index')
            ->with('success', __('Receiving Note Updated Successfully'));
    }

    public function destroy(ReceivingNote $receivingNote)
    {
        $receivingNote->delete();

        return redirect()
            ->route('receiving-notes.index')
            ->with('success', __('Receiving Note Deleted Successfully'));
    }

    public function downloadExcel(ReceivingNote $receivingNote)
    {
        return Excel::download(
            new ReceivingNoteExport($receivingNote),
            'receiving-note-' . $receivingNote->id . '.xlsx'
        );
    }
}
