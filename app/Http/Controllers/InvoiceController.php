<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {
        //
    }

    public function create(Client $client)
    {
        return view('invoices.create', [
            'client' => $client,
            'products' => Product::all(),
        ]);
    }

    public function store(Request $request, Client $client)
    {
        $validated = $request->validate([
            'invoice_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $total = collect($validated['items'])->sum(fn($item) => $item['quantity'] * $item['unit_price']);

        DB::transaction(function () use ($client, $validated, $total) {
            $invoice = $client->invoices()->create([
                'invoice_date' => $validated['invoice_date'],
                'total_amount' => $total,
                'notes' => $validated['notes'] ?? null
            ]);

            foreach ($validated['items'] as $item) {
                $invoice->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price']
                ]);
            }
        });

        return redirect()->route('clients.show', $client)
            ->with('success', __('Invoice created successfully'));
    }

    public function show(Invoice $invoice)
    {
        //
    }

    public function edit(Invoice $invoice)
    {
        //
    }

    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    public function destroy(Invoice $invoice)
    {
        //
    }
}
