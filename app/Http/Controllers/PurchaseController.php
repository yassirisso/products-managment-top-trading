<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function storeForClient(Request $request, Client $client)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'purchased_at' => 'required|date',
        ]);

        $client->purchases()->create($validated);

        return redirect()->route('clients.show', $client)
            ->with('success', 'Purchase recorded successfully');
    }
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
        $products = Product::all();
        return view('purchases.create', compact('client', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // app/Http/Controllers/PurchaseController.php
    public function store(Request $request, Client $client)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'purchase_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $client->products()->attach($validated['product_id'], [
            'price' => $validated['price'],
            'quantity' => $validated['quantity'],
            'purchase_date' => $validated['purchase_date'],
            'notes' => $validated['notes']
        ]);

        return redirect()->route('clients.show', $client)
            ->with('success', 'Purchase recorded successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client, Purchase $purchase)
    {
        $products = Product::all();
        return view('purchases.edit', compact('client', 'purchase', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client, Purchase $purchase)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'purchase_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $purchase->update($validated);

        return redirect()->route('clients.show', $client)
            ->with('success', 'Purchase updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client, Purchase $purchase)
    {
        $purchase->delete();
        return redirect()->route('clients.show', $client)
            ->with('success', 'Purchase deleted successfully');
    }
}
