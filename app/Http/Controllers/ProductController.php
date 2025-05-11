<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('reference', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(10);

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
        $product = Product::create([
            'reference' => $request->reference,
            'price' => $request->price
        ]);

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
        return view('products.edit', [
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'reference' => 'required|string|max:50|unique:products,reference,'.$product->id,
            'price' => 'required|numeric|min:0'
        ]);

        $product->update($validatedData);

        return redirect()->route('products.index', $product)
            ->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index', $product)
            ->with('success', 'Product deleted successfully');
    }

    public function import(Request $request)
    {
        $path = $request->file('file')->getRealPath();
        $file = fopen($path, 'r');

        $header = fgetcsv($file, 0, ';');

        while (($line = fgets($file)) !== false) {
            // Detect and convert encoding
            $encoding = mb_detect_encoding($line, ['UTF-8', 'GB2312', 'BIG5', 'SJIS', 'ISO-8859-1', 'Windows-1252'], true);
            $utf8Line = mb_convert_encoding($line, 'UTF-8', $encoding ?: 'UTF-8');

            // Parse CSV from the UTF-8 encoded line
            $row = str_getcsv($utf8Line, ';');

            $item_no = $row[0];
            $raw_price = $row[1] ?? null;

            if (!empty($item_no)) {
                $price = null;
                if ($raw_price) {
                    // Remove unwanted characters and replace comma with a period for decimal
                    $price = preg_replace('/[^\d,]/', '', $raw_price);
                    $price = str_replace(',', '.', $price); // Convert comma to decimal point
                }
                Product::create([
                    'reference' => $item_no,
                    'price' => $price
                ]);
            }
        }

        fclose($file);

        return redirect()->back()->with('success', 'Products imported successfully!');
    }
}
