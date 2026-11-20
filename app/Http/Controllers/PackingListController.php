<?php

namespace App\Http\Controllers;

use App\Models\PackingList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackingListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packingLists = PackingList::with('client')->latest()->get();
        return view('packing-lists.index', compact('packingLists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = \App\Models\Client::orderBy('name')->get();
        $products = \App\Models\Product::orderBy('reference')->get();
        $suppliers = \App\Models\Supplier::orderBy('name')->get();

        return view('packing-lists.create', compact('clients', 'products', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate basic info
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'port_of_loading' => 'nullable|string',
            'port_of_discharge' => 'nullable|string',
            'date' => 'nullable|date',
            'container_no' => 'nullable|string',
            'seal_no' => 'nullable|string',
            'products' => 'required|array',
        ]);

        // Use a database transaction to ensure data integrity
        DB::transaction(function () use ($validated, $request) {

            // 2. Create the Packing List
            $packingList = PackingList::create([
                'client_id' => $validated['client_id'],
                'port_of_loading' => $validated['port_of_loading'] ?? null,
                'port_of_discharge' => $validated['port_of_discharge'] ?? null,
                'date' => $validated['date'] ?? null,
                'container_no' => $validated['container_no'] ?? null,
                'seal_no' => $validated['seal_no'] ?? null,
            ]);

            // 3. Attach the products with the Pivot Data
            $productsData = [];

            foreach ($request->products as $product) {
                // Skip if no product ID was selected
                if (empty($product['id'])) continue;

                $ctn = (float) ($product['ctn'] ?? 0);
                $unit_cbm = (float) ($product['unit_cbm'] ?? 0);
                $unit_gw = (float) ($product['unit_gw'] ?? 0);
                $unit_nw = (float) ($product['unit_nw'] ?? 0);

                $productsData[$product['id']] = [
                    'ctn' => $ctn,
                    'pcs_cts' => $product['pcs_cts'] ?? 0,
                    'unit_cbm' => $unit_cbm,
                    'unit_gw' => $unit_gw,
                    'unit_nw' => $unit_nw,

                    // Calculate totals before saving to database
                    'total_cbm' => ($ctn * $unit_cbm),
                    'total_gw' => ($ctn * $unit_gw),
                    'total_nw' => ($ctn * $unit_nw),
                ];
            }

            // Sync the data to the pivot table
            $packingList->products()->sync($productsData);
        });

        return redirect()->route('packing-lists.index')
            ->with('success', __('messages.packing_list_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(PackingList $packingList)
    {
        $packingList->load('client', 'products');
        return view('packing-lists.show', compact('packingList'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PackingList $packingList)
    {
        $clients = \App\Models\Client::orderBy('name')->get();
        $products = \App\Models\Product::orderBy('reference')->get();
        // Assuming you need suppliers for the modal in edit view too
        $suppliers = \App\Models\Supplier::orderBy('name')->get();

        $packingList->load('products');

        return view('packing-lists.edit', compact('packingList', 'clients', 'products', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PackingList $packingList)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'port_of_loading' => 'nullable|string',
            'port_of_discharge' => 'nullable|string',
            'date' => 'nullable|date',
            'container_no' => 'nullable|string',
            'seal_no' => 'nullable|string',
            'products' => 'required|array',
        ]);

        DB::transaction(function () use ($validated, $request, $packingList) {
            $packingList->update([
                'client_id' => $validated['client_id'],
                'port_of_loading' => $validated['port_of_loading'] ?? null,
                'port_of_discharge' => $validated['port_of_discharge'] ?? null,
                'date' => $validated['date'] ?? null,
                'container_no' => $validated['container_no'] ?? null,
                'seal_no' => $validated['seal_no'] ?? null,
            ]);

            $productsData = [];
            foreach ($request->products as $product) {
                if (empty($product['id'])) continue;

                $ctn = (float) ($product['ctn'] ?? 0);
                $unit_cbm = (float) ($product['unit_cbm'] ?? 0);
                $unit_gw = (float) ($product['unit_gw'] ?? 0);
                $unit_nw = (float) ($product['unit_nw'] ?? 0);

                $productsData[$product['id']] = [
                    'ctn' => $ctn,
                    'pcs_cts' => $product['pcs_cts'] ?? 0,
                    'unit_cbm' => $unit_cbm,
                    'unit_gw' => $unit_gw,
                    'unit_nw' => $unit_nw,
                    'total_cbm' => ($ctn * $unit_cbm),
                    'total_gw' => ($ctn * $unit_gw),
                    'total_nw' => ($ctn * $unit_nw),
                ];
            }

            $packingList->products()->sync($productsData);
        });

        return redirect()->route('packing-lists.index')
            ->with('success', __('messages.packing_list_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PackingList $packingList)
    {
        $packingList->delete();

        return redirect()->route('packing-lists.index')
            ->with('success', __('messages.packing_list_deleted_successfully'));
    }
}
