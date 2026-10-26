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
    public function index(Request $request)
    {
        $query = CommercialInvoice::with('client');


        if ($request->search) {

            $query->where(function ($q) use ($request) {

                $q->where('invoice_no', 'like', '%' . $request->search . '%')
                    ->orWhereHas('client', function ($client) use ($request) {

                        $client->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }


        $commercialInvoices = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();


        return view(
            'commercial-invoices.index',
            compact('commercialInvoices')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $clients = Client::all();

        $products = Product::all();

        $bankAccounts = auth()->user()->bankAccounts;

        // Prefill from a proforma invoice if requested
        $proformaInvoice = null;

        if ($request->filled('from_proforma')) {

            $proformaInvoice = \App\Models\ProformaInvoice::with('products')
                ->findOrFail($request->from_proforma);

            // If already converted, redirect to the existing commercial invoice
            if ($commercial = $proformaInvoice->commercialInvoice) {
                return redirect()
                    ->route('commercial-invoices.show', $commercial)
                    ->with('success', 'This proforma invoice has already been converted.');
            }
        }

        return view(
            'commercial-invoices.create',
            compact(
                'clients',
                'products',
                'bankAccounts',
                'proformaInvoice'
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

            'proforma_invoice_id' => 'nullable|exists:proforma_invoices,id',

            'date' => 'nullable|date',

            'invoice_no' => 'required|string|max:255',

            'port_of_loading' => 'nullable|string',

            'port_of_discharge' => 'nullable|string',

            'mode_of_delivery' => 'nullable|string',

            'country_of_origin' => 'nullable|string',

            'bank_account_id' => 'nullable|exists:bank_accounts,id',

            'products' => 'nullable|array',
            'currency' => 'required|in:RMB,USD',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        // Prevent double conversion
        if ($request->filled('proforma_invoice_id')) {

            $alreadyConverted = CommercialInvoice::where(
                'proforma_invoice_id',
                $request->proforma_invoice_id
            )->exists();

            if ($alreadyConverted) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'proforma_invoice_id' => 'This proforma invoice has already been converted.'
                    ]);
            }
        }

        $commercialInvoice = CommercialInvoice::create([

            'client_id' => $request->client_id,

            'proforma_invoice_id' => $request->proforma_invoice_id,

            'date' => $request->date,

            'invoice_no' => $request->invoice_no,

            'port_of_loading' => $request->port_of_loading,

            'port_of_discharge' => $request->port_of_discharge,

            'mode_of_delivery' => $request->mode_of_delivery,

            'country_of_origin' => $request->country_of_origin,

            'bank_account_id' => $request->bank_account_id,

            'currency' => $request->currency,
            'discount_percent' => $request->discount_percent ?? 0,
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
            ->route('commercial-invoices.show', $commercialInvoice)
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
        $clients = Client::all();

        $products = Product::all();

        $bankAccounts = auth()->user()->bankAccounts;

        $commercialInvoice->load('products');

        return view(
            'commercial-invoices.edit',
            compact(
                'commercialInvoice',
                'clients',
                'products',
                'bankAccounts'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CommercialInvoice $commercialInvoice)
    {
        $validated = $request->validate([

            'client_id' => 'required|exists:clients,id',

            'date' => 'nullable|date',

            'invoice_no' => 'required|string|max:255',

            'port_of_loading' => 'nullable|string',

            'port_of_discharge' => 'nullable|string',

            'mode_of_delivery' => 'nullable|string',

            'country_of_origin' => 'nullable|string',

            'bank_account_id' => 'nullable|exists:bank_accounts,id',

            'products' => 'nullable|array',
            'currency' => 'required|in:RMB,USD',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        $commercialInvoice->update([

            'client_id' => $request->client_id,

            'date' => $request->date,

            'invoice_no' => $request->invoice_no,

            'port_of_loading' => $request->port_of_loading,

            'port_of_discharge' => $request->port_of_discharge,

            'mode_of_delivery' => $request->mode_of_delivery,

            'country_of_origin' => $request->country_of_origin,

            'bank_account_id' => $request->bank_account_id,

            'currency' => $request->currency,
            'discount_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        // Reset products
        $commercialInvoice->products()->detach();

        // Re-attach from form
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
            ->route('commercial-invoices.show', $commercialInvoice)
            ->with('success', 'Commercial Invoice Updated Successfully');
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
