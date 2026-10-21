<?php

namespace App\Http\Controllers;

use App\Models\CommercialInvoice;
use App\Models\ShippingCompany;
use App\Models\ShippingCompanyContact;
use App\Models\ShippingFee;
use Illuminate\Http\Request;

class ShippingCompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = ShippingCompany::withCount(['contacts', 'fees']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('tel', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $shippingCompanies = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('shipping-companies.index', compact('shippingCompanies'));
    }

    public function create()
    {
        return view('shipping-companies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:shipping_companies',
            'tel' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        ShippingCompany::create($validated);

        return redirect()->route('shipping-companies.index')
            ->with('success', 'Shipping company created successfully.');
    }

    public function show(ShippingCompany $shippingCompany)
    {
        $shippingCompany->load(['contacts', 'fees.commercialInvoice.client']);

        $commercialInvoices = CommercialInvoice::with('client')
            ->latest()
            ->get();

        return view('shipping-companies.show', compact('shippingCompany', 'commercialInvoices'));
    }

    public function edit(ShippingCompany $shippingCompany)
    {
        return view('shipping-companies.edit', compact('shippingCompany'));
    }

    public function update(Request $request, ShippingCompany $shippingCompany)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:shipping_companies,name,' . $shippingCompany->id,
            'tel' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        $shippingCompany->update($validated);

        return redirect()->route('shipping-companies.index')
            ->with('success', 'Shipping company updated successfully.');
    }

    public function destroy(ShippingCompany $shippingCompany)
    {
        $shippingCompany->delete();

        return redirect()->route('shipping-companies.index')
            ->with('success', 'Shipping company deleted successfully.');
    }

    public function storeContact(Request $request, ShippingCompany $shippingCompany)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'position' => 'nullable|string|max:255',
        ]);

        $shippingCompany->contacts()->create($validated);

        return back()->with('success', 'Contact added successfully.');
    }

    public function destroyContact(ShippingCompany $shippingCompany, ShippingCompanyContact $contact)
    {
        $contact->delete();

        return back()->with('success', 'Contact removed successfully.');
    }

    public function storeFee(Request $request, ShippingCompany $shippingCompany)
    {
        $validated = $request->validate([
            'commercial_invoice_id' => 'required|exists:commercial_invoices,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|in:RMB,USD',
            'date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        $shippingCompany->fees()->create($validated);

        return back()->with('success', 'Shipping fee added successfully.');
    }

    public function destroyFee(ShippingCompany $shippingCompany, ShippingFee $fee)
    {
        $fee->delete();

        return back()->with('success', 'Shipping fee removed successfully.');
    }
}
