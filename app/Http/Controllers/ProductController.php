<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

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
        // Validation
        $request->validate([
            'reference' => 'required|string|max:50|unique:products,reference',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'supplier_id' => 'required|exists:suppliers,id',
            'pcs_cts' => 'nullable|integer|min:0',
            'unit_cbm' => 'nullable|numeric|min:0',
            'unit_gw' => 'nullable|numeric|min:0',
            'unit_nw' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ], [
            'reference.unique' => 'This product reference already exists.',
        ]);


        // Upload image if exists
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }


        // Create product
        $product = Product::create([
            'reference' => $request->reference,
            'price' => $request->price,
            'image' => $imagePath,
            'pcs_cts' => $request->pcs_cts,
            'unit_cbm' => $request->unit_cbm,
            'unit_gw' => $request->unit_gw,
            'unit_nw' => $request->unit_nw,
            'description' => $request->description,
        ]);


        // Attach supplier automatically in product_supplier table
        $product->suppliers()->attach($request->supplier_id, [
            'buying_price' => $request->price
        ]);


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
        $suppliers = Supplier::all();

        return view('products.edit', compact('product', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'reference' => 'required|string|max:50|unique:products,reference,' . $product->id,
            'price' => 'required|numeric|min:0',
            'supplier_id' => 'required|exists:suppliers,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'pcs_cts' => 'nullable|integer|min:0',
            'unit_cbm' => 'nullable|numeric|min:0',
            'unit_gw' => 'nullable|numeric|min:0',
            'unit_nw' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        // Upload new image if selected
        if ($request->hasFile('image')) {

            // Store image
            $imagePath = $request->file('image')->store('products', 'public');

            // Save path into validated data
            $validatedData['image'] = $imagePath;
        }

        // Update product
        $product->update($validatedData);

        // Update supplier in product_supplier table
        $product->suppliers()->sync([
            $request->supplier_id => [
                'buying_price' => $request->price
            ]
        ]);

        return redirect()->route('products.index')
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
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Storage::disk('public')->makeDirectory('products');
        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getRealPath());

        $invoiceSheet = $spreadsheet->getSheet(1);
        $invoiceData = $this->processSheet($invoiceSheet);

        $products = array_merge($invoiceData);

        DB::beginTransaction();
        try {
            foreach ($products as $itemNo => $productData) {
                \Log::debug("Processing product {$itemNo}", [
                    'price' => $productData['price'],
                    'image_path' => $productData['image_path']
                ]);

                // Convert empty price to null
                $price = $this->normalizePrice($productData['price']);

                Product::updateOrCreate(
                    ['reference' => $itemNo],
                    [
                        'price' => $price,
                        'image_path' => $productData['image_path'],
                    ]
                );
            }

            DB::commit();
            return redirect()->back()->with('success', 'Products imported successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Import failed: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error importing products: ' . $e->getMessage());
        }
    }

    private function normalizePrice($price)
    {
        if (is_null($price)) {
            return null;
        }

        // Handle string prices with currency symbols or commas
        if (is_string($price)) {
            $price = str_replace(['$', '€', '£', ',', ' '], '', $price);
        }

        // Convert to float if numeric
        return is_numeric($price) ? (float)$price : null;
    }

    private function processSheet($sheet)
    {
        $data = [];
        $headers = [];
        $startCollecting = false;
        $drawings = $sheet->getDrawingCollection();

        $imageMap = [];
        foreach ($drawings as $drawing) {
            $cell = $drawing->getCoordinates();
            $imageMap[$cell] = $drawing;
        }

        foreach ($sheet->getRowIterator() as $row) {
            $rowIndex = $row->getRowIndex();
            $rowData = [];
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }

            if (empty(array_filter($rowData))) continue;

            if (in_array('ITEM NO', $rowData)) {
                $headers = $rowData;
                $startCollecting = true;
                continue;
            }

            if (!$startCollecting) continue;

            $rowData = array_pad($rowData, count($headers), null);
            $rowData = array_combine($headers, $rowData);

            $itemNo = trim($rowData['ITEM NO'] ?? '');
            if (empty($itemNo)) continue;

            $imagePath = null;
            $photoCell = 'B' . $rowIndex;

            if (isset($imageMap[$photoCell])) {
                $imagePath = $this->saveImageFromExcel($imageMap[$photoCell], $itemNo);
            }

            $data[$itemNo] = [
                'price' => $rowData['PRICE'] ?? null, // Don't convert here - we'll normalize later
                'image_path' => $imagePath,
            ];
        }

        return $data;
    }

    private function saveImageFromExcel($drawing, $reference)
    {
        try {
            $imageData = null;
            $extension = 'jpg'; // default extension

            if ($drawing instanceof MemoryDrawing) {
                // Handle memory drawings
                ob_start();
                switch ($drawing->getMimeType()) {
                    case MemoryDrawing::MIMETYPE_PNG:
                        imagepng($drawing->getImageResource());
                        $extension = 'png';
                        break;
                    case MemoryDrawing::MIMETYPE_JPEG:
                        imagejpeg($drawing->getImageResource());
                        $extension = 'jpg';
                        break;
                    case MemoryDrawing::MIMETYPE_GIF:
                        imagegif($drawing->getImageResource());
                        $extension = 'gif';
                        break;
                }
                $imageData = ob_get_clean();
            } else {
                // Handle regular drawings
                $zipReader = fopen($drawing->getPath(), 'rb');
                $imageData = stream_get_contents($zipReader);
                fclose($zipReader);

                // Try to get extension from filename
                $extension = pathinfo($drawing->getPath(), PATHINFO_EXTENSION) ?: 'jpg';
            }

            $filename = 'products/' . $reference . '.' . $extension;

            // Save directly to storage without 'public/' prefix
            $result = Storage::disk('public')->put($filename, $imageData);

            if ($result === false) {
                \Log::error("Failed to save image for {$reference} to {$filename}");
                return null;
            }

            // Return just the relative path without 'public/'
            return $filename;

        } catch (\Exception $e) {
            \Log::error("Error saving image for {$reference}: " . $e->getMessage());
            return null;
        }
    }
}
