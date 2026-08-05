<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Product;
use App\Models\ProformaInvoice;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProformaInvoiceExport;

class ProformaInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ProformaInvoice::with('client');


        if ($request->search) {

            $query->whereHas('client', function ($client) use ($request) {

                $client->where('name', 'like', '%' . $request->search . '%');
            });
        }


        $proformaInvoices = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();


        return view(
            'proforma-invoices.index',
            compact('proformaInvoices')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();

        $products = Product::all();

        return view(
            'proforma-invoices.create',
            compact('clients', 'products')
        );
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

            'local_charge' => 'nullable|numeric',

            'products' => 'nullable|array',
            'currency' => 'required|in:RMB,USD',

        ]);

        // CREATE PROFORMA INVOICE
        $proformaInvoice = ProformaInvoice::create([

            'client_id' => $request->client_id,

            'port_of_loading' => $request->port_of_loading,
            'port_of_discharge' => $request->port_of_discharge,

            'date' => $request->date,

            'container_no' => $request->container_no,
            'seal_no' => $request->seal_no,


            'local_charge' => $request->local_charge ?? 0,
            'currency' => $request->currency,

        ]);

        // ATTACH PRODUCTS
        if ($request->has('products')) {

            foreach ($request->products as $productData) {

                if (!empty($productData['id'])) {

                    $product = Product::find($productData['id']);

                    $qty = ($productData['ctn'] ?? 0) * ($product->pcs_cts ?? 0);

                    $amount = $qty * ($productData['unit_price'] ?? 0);

                    $proformaInvoice->products()->attach(
                        $productData['id'],
                        [
                            'ctn' => $productData['ctn'] ?? 0,

                            'unit_price' => $productData['unit_price'] ?? 0,
                        ]
                    );
                }
            }
        }

        return redirect()
            ->route('proforma-invoices.index')
            ->with('success', 'Proforma Invoice Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProformaInvoice $proformaInvoice)
    {
        $proformaInvoice->load([
            'client',
            'products'
        ]);

        return view(
            'proforma-invoices.show',
            compact('proformaInvoice')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProformaInvoice $proformaInvoice)
    {
        $clients = Client::all();

        $products = Product::all();

        $proformaInvoice->load('products');

        return view(
            'proforma-invoices.edit',
            compact(
                'proformaInvoice',
                'clients',
                'products'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProformaInvoice $proformaInvoice)
    {
        $validated = $request->validate([

            'client_id' => 'required|exists:clients,id',

            'port_of_loading' => 'nullable|string',
            'port_of_discharge' => 'nullable|string',

            'date' => 'nullable|date',

            'container_no' => 'nullable|string',
            'seal_no' => 'nullable|string',

            'local_charge' => 'nullable|numeric',

            'products' => 'nullable|array',

        ]);

        // UPDATE INVOICE
        $proformaInvoice->update([

            'client_id' => $request->client_id,

            'port_of_loading' => $request->port_of_loading,
            'port_of_discharge' => $request->port_of_discharge,

            'date' => $request->date,

            'container_no' => $request->container_no,
            'seal_no' => $request->seal_no,

            'local_charge' => $request->local_charge ?? 0,

        ]);

        // RESET PRODUCTS
        $proformaInvoice->products()->detach();

        // RE-ATTACH PRODUCTS
        if ($request->has('products')) {

            foreach ($request->products as $productData) {

                if (!empty($productData['id'])) {

                    $proformaInvoice->products()->attach(
                        $productData['id'],
                        [
                            'ctn' => $productData['ctn'] ?? 0,

                            'unit_price' => $productData['unit_price'] ?? 0,
                        ]
                    );
                }
            }
        }

        return redirect()
            ->route('proforma-invoices.index')
            ->with('success', 'Proforma Invoice Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(ProformaInvoice $proformaInvoice)
    // {
    //     $proformaInvoice->delete();

    //     return redirect()
    //         ->route('proforma-invoices.index')
    //         ->with('success', 'Proforma Invoice Deleted Successfully');
    // }

    public function downloadExcel(ProformaInvoice $proformaInvoice)
    {
        return Excel::download(
            new ProformaInvoiceExport($proformaInvoice),
            'proforma-invoice-' . $proformaInvoice->id . '.xlsx'
        );
    }
}
