<?php

namespace App\Http\Controllers;

use App\Exports\CommercialInvoiceExport;
use App\Models\Client;
use App\Models\CommercialInvoice;
use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CommercialInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $commercialInvoices = CommercialInvoice::with('client')
            ->latest()
            ->get();

        return view(
            'commercial-invoices.index',
            compact('commercialInvoices')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();

        $products = Product::all();

        $bankAccounts =
            auth()->user()->bankAccounts;

        return view(
            'commercial-invoices.create',
            compact(
                'clients',
                'products',
                'bankAccounts'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'client_id' => 'required|exists:clients,id',

            'date' => 'nullable|date',

            'invoice_no' => 'nullable|string',

            'port_of_loading' => 'nullable|string',

            'port_of_discharge' => 'nullable|string',

            'mode_of_delivery' => 'nullable|string',

            'country_of_origin' => 'nullable|string',

            'bank_account_id' => 'nullable|exists:bank_accounts,id',

            'products' => 'nullable|array',
            'currency' => 'required|in:RMB,USD',
        ]);

        $commercialInvoice = CommercialInvoice::create([

            'client_id' => $request->client_id,

            'date' => $request->date,

            'invoice_no' => $request->invoice_no,

            'port_of_loading' => $request->port_of_loading,

            'port_of_discharge' => $request->port_of_discharge,

            'mode_of_delivery' => $request->mode_of_delivery,

            'country_of_origin' => $request->country_of_origin,

            'bank_account_id' => $request->bank_account_id,

            'currency' => $request->currency,
        ]);

        if ($request->has('products')) {

            foreach ($request->products as $productData) {

                if (!empty($productData['id'])) {

                    $commercialInvoice->products()->attach(
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
            ->route('commercial-invoices.index')
            ->with('success', 'Commercial Invoice Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(CommercialInvoice $commercialInvoice)
    {
        $commercialInvoice->load([
            'client',
            'products'
        ]);

        return view(
            'commercial-invoices.show',
            compact('commercialInvoice')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CommercialInvoice $commercialInvoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CommercialInvoice $commercialInvoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CommercialInvoice $commercialInvoice)
    {
        //
    }

    public function downloadExcel(CommercialInvoice $commercialInvoice) 
    {
        return Excel::download(
            new CommercialInvoiceExport(
                $commercialInvoice
            ),
            'commercial-invoice-' .
            $commercialInvoice->id .
            '.xlsx'
        );
    }
}
