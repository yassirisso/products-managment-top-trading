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
    public function index(Request $request)
    {
        $query = PackingList::with('client');

        if ($request->search) {
            $query->whereHas('client', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $packingLists = $query->latest()->paginate(10)->withQueryString();

        return view('packing-lists.index', compact('packingLists'));
    }

    public function create()
    {
        $clients = Client::all();
        $products = Product::all();

        return view('packing-lists.create', compact('clients', 'products'));
    }

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

        $packingList = PackingList::create([
            'client_id' => $request->client_id,
            'port_of_loading' => $request->port_of_loading,
            'port_of_discharge' => $request->port_of_discharge,
            'date' => $request->date,
            'container_no' => $request->container_no,
            'seal_no' => $request->seal_no,
        ]);

        if ($request->has('products')) {
            foreach ($request->products as $productData) {
                if (!empty($productData['id'])) {
                    $packingList->products()->attach(
                        $productData['id'],
                        ['ctn' => $productData['ctn'] ?? 0]
                    );
                }
            }
        }

        return redirect()
            ->route('packing-lists.index')
            ->with('success', __('Packing List Created Successfully'));
    }

    public function show(PackingList $packingList)
    {
        $packingList->load(['client', 'products']);
        return view('packing-lists.show', compact('packingList'));
    }

    public function edit(PackingList $packingList)
    {
        $clients = \App\Models\Client::all();
        $products = \App\Models\Product::all();
        $packingList->load('products');

        return view('packing-lists.edit', compact('packingList', 'clients', 'products'));
    }

    public function update(Request $request, PackingList $packingList)
    {
        $validated = $request->validate([
            'client_id'         => 'required|exists:clients,id',
            'port_of_loading'   => 'nullable|string|max:255',
            'port_of_discharge' => 'nullable|string|max:255',
            'date'              => 'nullable|date',
            'container_no'      => 'nullable|string|max:255',
            'seal_no'           => 'nullable|string|max:255',
            'products'          => 'nullable|array',
        ]);

        $packingList->update([
            'client_id'         => $validated['client_id'],
            'port_of_loading'   => $validated['port_of_loading'] ?? null,
            'port_of_discharge' => $validated['port_of_discharge'] ?? null,
            'date'              => $validated['date'] ?? null,
            'container_no'      => $validated['container_no'] ?? null,
            'seal_no'           => $validated['seal_no'] ?? null,
        ]);

        $sync = [];
        if (!empty($validated['products'])) {
            foreach ($validated['products'] as $row) {
                if (!empty($row['id'])) {
                    $sync[$row['id']] = ['ctn' => $row['ctn'] ?? 0];
                }
            }
        }

        $packingList->products()->sync($sync);

        return redirect()
            ->route('packing-lists.show', $packingList)
            ->with('success', __('Packing list updated successfully.'));
    }

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
