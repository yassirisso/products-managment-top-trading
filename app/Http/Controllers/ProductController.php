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

    // public function import(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|mimes:xlsx,xls'
    //     ]);

    //     // Ensure storage directory exists
    //     Storage::disk('public')->makeDirectory('products');

    //     $file = $request->file('file');
    //     $spreadsheet = IOFactory::load($file->getRealPath());

    //     // Process sheets
    //     $invoiceSheet = $spreadsheet->getSheet(1);
    //     $invoiceData = $this->processSheet($invoiceSheet);

    //     $packingSheet = $spreadsheet->getSheet(0);
    //     $packingData = $this->processSheet($packingSheet);

    //     // Combine data
    //     $products = array_merge($invoiceData, $packingData);

    //     DB::beginTransaction();
    //     try {
    //         foreach ($products as $itemNo => $productData) {
    //             $product = Product::updateOrCreate(
    //                 ['reference' => $itemNo],
    //                 [
    //                     'price' => $productData['price'],
    //                     'image_path' => $productData['image_path'],
    //                 ]
    //             );

    //             // Debug output
    //             if ($productData['image_path']) {
    //                 \Log::info("Saved product {$itemNo}", [
    //                     'image_path' => $productData['image_path'],
    //                     'storage_exists' => Storage::disk('public')->exists($productData['image_path'])
    //                 ]);
    //             }
    //         }

    //         DB::commit();
    //         return redirect()->back()->with('success', 'Products imported successfully!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         \Log::error("Import failed: " . $e->getMessage());
    //         return redirect()->back()->with('error', 'Error importing products: ' . $e->getMessage());
    //     }
    // }

    // private function processSheet($sheet)
    // {
    //     $data = [];
    //     $headers = [];
    //     $startCollecting = false;
    //     $drawings = $sheet->getDrawingCollection();

    //     // Create mapping of drawings to their cell positions
    //     $imageMap = [];
    //     foreach ($drawings as $drawing) {
    //         $cell = $drawing->getCoordinates();
    //         $imageMap[$cell] = $drawing;
    //     }

    //     foreach ($sheet->getRowIterator() as $row) {
    //         $rowIndex = $row->getRowIndex();
    //         $rowData = [];
    //         $cellIterator = $row->getCellIterator();
    //         $cellIterator->setIterateOnlyExistingCells(false);

    //         foreach ($cellIterator as $cell) {
    //             $rowData[] = $cell->getValue();
    //         }

    //         // Skip empty rows
    //         if (empty(array_filter($rowData))) continue;

    //         // Detect header row
    //         if (in_array('ITEM NO', $rowData)) {
    //             $headers = $rowData;
    //             $startCollecting = true;
    //             continue;
    //         }

    //         if (!$startCollecting) continue;

    //         // Map data to headers
    //         $rowData = array_pad($rowData, count($headers), null);
    //         $rowData = array_combine($headers, $rowData);

    //         $itemNo = trim($rowData['ITEM NO'] ?? '');
    //         if (empty($itemNo)) continue;

    //         // Process image from PHOTO column (column B)
    //         $imagePath = null;
    //         $photoCell = 'B' . $rowIndex;

    //         if (isset($imageMap[$photoCell])) {
    //             $imagePath = $this->saveImageFromExcel($imageMap[$photoCell], $itemNo);
    //             \Log::debug("Processing image for {$itemNo}", [
    //                 'cell' => $photoCell,
    //                 'image_path' => $imagePath,
    //                 'storage_exists' => $imagePath ? Storage::disk('public')->exists($imagePath) : false
    //             ]);
    //         }

    //         $data[$itemNo] = [
    //             'price' => is_numeric($rowData['PRICE'] ?? null) ? (float)$rowData['PRICE'] : null,
    //             'image_path' => $imagePath,
    //         ];
    //     }

    //     return $data;
    // }

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

        $packingSheet = $spreadsheet->getSheet(0);
        $packingData = $this->processSheet($packingSheet);

        $products = array_merge($invoiceData, $packingData);

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
