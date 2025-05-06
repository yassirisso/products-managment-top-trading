<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Client;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['suppliers', 'clients'])
            ->when(request('search'), function ($query) {
                $query->where('reference', 'like', '%' . request('search') . '%');
            })
            ->paginate(10);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $clients = Client::all();
        return view('products.create', compact('suppliers', 'clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = Product::create($request->only('reference'));

        // Attach suppliers with buying prices
        if ($request->has('suppliers')) {
            foreach ($request->suppliers as $supplierId => $buyingPrice) {
                $product->suppliers()->attach($supplierId, ['buying_price' => $buyingPrice]);
            }
        }

        // Attach clients with selling prices
        if ($request->has('clients')) {
            foreach ($request->clients as $clientId => $sellingPrice) {
                $product->clients()->attach($clientId, ['price' => $sellingPrice]);
            }
        }

        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }

    public function import(Request $request)
    {
        $file = fopen($request->file('file')->getRealPath(), 'r');

        // Skip the header row
        $header = fgetcsv($file, 0, ';');

        while (($row = fgetcsv($file, 0, ';')) !== false) {
            $item_no = $row[0]; // Only the item_no column

            if (!empty($item_no)) {
                Product::create([
                    'reference' => $item_no,
                ]);
            }
        }

        fclose($file);

        return redirect()->back()->with('success', 'Products imported successfully!');
    }
}
