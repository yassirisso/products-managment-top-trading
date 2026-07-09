<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::with('products.clients')
            ->latest()
            ->get();

        $suppliers->each(function ($supplier) {

            $clients = $supplier->products
                ->flatMap(function ($product) {
                    return $product->clients;
                })
                ->unique('id');

            $supplier->clients_count = $clients->count();
        });

        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:suppliers',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
        ]);

        Supplier::create($validated);

        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load('products');
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        $availableProducts = Product::whereDoesntHave('suppliers', function($query) use ($supplier) {
            $query->where('supplier_id', $supplier->id);
        })->get();

        return view('suppliers.edit', compact('supplier', 'availableProducts'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name,' . $supplier->id,
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }

    public function attachProduct(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'buying_price' => 'required|numeric|min:0',
        ]);

        $supplier->products()->syncWithoutDetaching([
            $validated['product_id'] => ['buying_price' => $validated['buying_price']]
        ]);

        return back()->with('success', 'Product added to supplier successfully.');
    }

    public function detachProduct(Supplier $supplier, $productId)
    {
        $supplier->products()->detach($productId);
        return back()->with('success', 'Product removed from supplier successfully.');
    }

    public function clients(Supplier $supplier)
    {
        $supplier->load([
            'products.commercialInvoices.client',
            'products.invoices.client',
            'products.proformaInvoices.client',
        ]);


        $clients = collect();


        foreach ($supplier->products as $product) {


            // Commercial invoices
            foreach ($product->commercialInvoices as $invoice) {

                $clients->push([
                    'client' => $invoice->client,
                    'product_id' => $product->id
                ]);

            }


            // Normal invoices
            foreach ($product->invoices as $invoice) {

                $clients->push([
                    'client' => $invoice->client,
                    'product_id' => $product->id
                ]);

            }


            // Proforma invoices
            foreach ($product->proformaInvoices as $invoice) {

                $clients->push([
                    'client' => $invoice->client,
                    'product_id' => $product->id
                ]);

            }

        }


        $clients = $clients
            ->groupBy(function ($item) {
                return $item['client']->id;
            })
            ->map(function ($items) {

                $client = $items->first()['client'];

                $client->products_count = $items
                    ->pluck('product_id')
                    ->unique()
                    ->count();

                return $client;

            })
            ->values();


        return view('suppliers.clients', compact(
            'supplier',
            'clients'
        ));
    }
}
