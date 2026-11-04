<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ShippingMark;
use App\Models\WarehouseFee;
use Illuminate\Http\Request;

class WarehouseFeeController extends Controller
{
    public function index()
    {
        $warehouseFees = WarehouseFee::with(['client', 'shippingMark'])
            ->latest('date')
            ->paginate(10);

        return view('warehouse-fees.index', compact('warehouseFees'));
    }

    public function create()
    {
        $clients = Client::all();
        $shippingMarks = ShippingMark::all();

        $proformaContainers = \App\Models\ProformaInvoice::whereNotNull('container_no')->where('container_no', '!=', '')->distinct()->pluck('container_no');
        $feeContainers = WarehouseFee::whereNotNull('container_no')->where('container_no', '!=', '')->distinct()->pluck('container_no');
        $containers = $proformaContainers->merge($feeContainers)->unique()->sort()->values();

        $proformaInvoices = \App\Models\ProformaInvoice::whereNotNull('container_no')->get();

        // Map container_no => list of client ids (to auto-fill client)
        $containerClients = \App\Models\ProformaInvoice::whereNotNull('container_no')
            ->where('container_no', '!=', '')
            ->select('container_no', 'client_id')
            ->get()
            ->groupBy('container_no')
            ->map(fn($group) => $group->pluck('client_id')->unique()->values()->all());

        return view('warehouse-fees.create', compact('clients', 'shippingMarks', 'containers', 'proformaInvoices', 'containerClients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'container_no' => 'required|string|max:255',
            'date' => 'required|date',
            'client_id' => 'required|exists:clients,id',
            'shipping_mark_id' => 'nullable|exists:shipping_marks,id',
            'montant_rmb' => 'required|numeric|min:0',
            'proforma_invoice_id' => 'nullable|exists:proforma_invoices,id',
        ]);

        WarehouseFee::create($validated);

        $this->updateProformaLocalCharge($request->proforma_invoice_id);

        return redirect()->route('warehouse-fees.index')
            ->with('success', __('仓库费用添加成功'));
    }

    public function edit(WarehouseFee $warehouseFee)
    {
        $clients = Client::all();
        $shippingMarks = ShippingMark::all();

        $proformaContainers = \App\Models\ProformaInvoice::whereNotNull('container_no')->where('container_no', '!=', '')->distinct()->pluck('container_no');
        $feeContainers = WarehouseFee::whereNotNull('container_no')->where('container_no', '!=', '')->distinct()->pluck('container_no');
        $containers = $proformaContainers->merge($feeContainers)->unique()->sort()->values();

        $proformaInvoices = \App\Models\ProformaInvoice::whereNotNull('container_no')->get();

        // Map container_no => list of client ids (to auto-fill client)
        $containerClients = \App\Models\ProformaInvoice::whereNotNull('container_no')
            ->where('container_no', '!=', '')
            ->select('container_no', 'client_id')
            ->get()
            ->groupBy('container_no')
            ->map(fn($group) => $group->pluck('client_id')->unique()->values()->all());

        return view('warehouse-fees.edit', compact('warehouseFee', 'clients', 'shippingMarks', 'containers', 'proformaInvoices', 'containerClients'));
    }

    public function update(Request $request, WarehouseFee $warehouseFee)
    {
        $validated = $request->validate([
            'container_no' => 'required|string|max:255',
            'date' => 'required|date',
            'client_id' => 'required|exists:clients,id',
            'shipping_mark_id' => 'nullable|exists:shipping_marks,id',
            'montant_rmb' => 'required|numeric|min:0',
            'proforma_invoice_id' => 'nullable|exists:proforma_invoices,id',
        ]);

        $oldProformaId = $warehouseFee->proforma_invoice_id;
        $warehouseFee->update($validated);

        // Update the new invoice
        $this->updateProformaLocalCharge($request->proforma_invoice_id);

        // If the fee was moved to a different invoice, update the old one too
        if ($oldProformaId && $oldProformaId != $request->proforma_invoice_id) {
            $this->updateProformaLocalCharge($oldProformaId);
        }

        return redirect()->route('warehouse-fees.index')
            ->with('success', __('仓库费用更新成功'));
    }

    public function destroy(WarehouseFee $warehouseFee)
    {
        $proformaId = $warehouseFee->proforma_invoice_id;
        $warehouseFee->delete();

        $this->updateProformaLocalCharge($proformaId);

        return redirect()->route('warehouse-fees.index')
            ->with('success', __('仓库费用删除成功'));
    }

    private function updateProformaLocalCharge($proformaInvoiceId)
    {
        if (!$proformaInvoiceId) return;

        $proformaInvoice = \App\Models\ProformaInvoice::find($proformaInvoiceId);
        if (!$proformaInvoice) return;

        $warehouseTotal = \App\Models\WarehouseFee::where('proforma_invoice_id', $proformaInvoiceId)->sum('montant_rmb');
        $shippingTotal  = \App\Models\ShippingFee::where('proforma_invoice_id', $proformaInvoiceId)->sum('amount');

        $proformaInvoice->update(['local_charge' => $warehouseTotal + $shippingTotal]);
    }
}
