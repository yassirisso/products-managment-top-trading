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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Client $client)
    {
        return view('invoices.create', [
            'client' => $client,
            'products' => Product::all(), // Or Product::select('id', 'reference')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
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

        // Calculate total using validated data
        $total = collect($validated['items'])->sum(fn($item) => $item['quantity'] * $item['unit_price']);

        DB::transaction(function () use ($client, $validated, $total) {
            $invoice = $client->invoices()->create([
                'invoice_date' => $validated['invoice_date'],
                'total_amount' => $total,
                'notes' => $validated['notes'] ?? null // Using null coalesce for nullable field
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
            ->with('success', 'Invoice created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
