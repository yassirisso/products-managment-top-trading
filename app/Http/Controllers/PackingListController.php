<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Product;
use App\Models\PackingList;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PackingListExport;

class PackingListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PackingList::with('client');


        if ($request->search) {

            $query->whereHas('client', function ($q) use ($request) {

                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }


        $packingLists = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();


        return view('packing-lists.index', compact('packingLists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();

        $products = Product::all();

        return view('packing-lists.create', compact('clients', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'client_id' => 'required|exists:clients,id',

            'port_of_loading' => 'nullable|string',
            'port_of_discharge' => 'nullable|string',

            'date' => 'nullable|date',

            'container_no' => 'nullable|string',
            'seal_no' => 'nullable|string',

            'products' => 'nullable|array',
        ]);

        // CREATE PACKING LIST
        $packingList = PackingList::create([

            'client_id' => $request->client_id,

            'port_of_loading' => $request->port_of_loading,
            'port_of_discharge' => $request->port_of_discharge,

            'date' => $request->date,

            'container_no' => $request->container_no,
            'seal_no' => $request->seal_no,
        ]);

        // ATTACH PRODUCTS
        if ($request->has('products')) {

            foreach ($request->products as $productData) {

                if (!empty($productData['id'])) {

                    $packingList->products()->attach(
                        $productData['id'],
                        [
                            'ctn' => $productData['ctn'] ?? 0,
                        ]
                    );
                }
            }
        }

        return redirect()
            ->route('packing-lists.index')
            ->with('success', 'Packing List Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(PackingList $packingList)
    {
        $packingList->load([
            'client',
            'products'
        ]);

        return view(
            'packing-lists.show',
            compact('packingList')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function downloadExcel(PackingList $packingList)
    {
        return Excel::download(
            new PackingListExport($packingList),
            'packing-list-' . $packingList->id . '.xlsx'
        );
    }
}
