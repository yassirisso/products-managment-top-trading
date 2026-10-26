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

        // Fetch unique container numbers from BOTH Proformas and Warehouse Fees
        $proformaContainers = \App\Models\ProformaInvoice::whereNotNull('container_no')
            ->where('container_no', '!=', '')
            ->distinct()
            ->pluck('container_no');

        $feeContainers = WarehouseFee::whereNotNull('container_no')
            ->where('container_no', '!=', '')
            ->distinct()
            ->pluck('container_no');

        $containers = $proformaContainers->merge($feeContainers)->unique()->sort()->values();

        return view('warehouse-fees.create', compact('clients', 'shippingMarks', 'containers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'container_no' => 'required|string|max:255',
            'date' => 'required|date',
            'client_id' => 'required|exists:clients,id',
            'shipping_mark_id' => 'nullable|exists:shipping_marks,id',
            'montant_rmb' => 'required|numeric|min:0',
        ]);

        WarehouseFee::create($validated);

        return redirect()->route('warehouse-fees.index')
            ->with('success', '仓库费用添加成功');
    }

    public function edit(WarehouseFee $warehouseFee)
    {
        $clients = Client::all();
        $shippingMarks = ShippingMark::all();

        // Fetch unique container numbers from BOTH Proformas and Warehouse Fees
        $proformaContainers = \App\Models\ProformaInvoice::whereNotNull('container_no')
            ->where('container_no', '!=', '')
            ->distinct()
            ->pluck('container_no');

        $feeContainers = WarehouseFee::whereNotNull('container_no')
            ->where('container_no', '!=', '')
            ->distinct()
            ->pluck('container_no');

        $containers = $proformaContainers->merge($feeContainers)->unique()->sort()->values();

        return view('warehouse-fees.edit', compact('warehouseFee', 'clients', 'shippingMarks', 'containers'));
    }

    public function update(Request $request, WarehouseFee $warehouseFee)
    {
        $validated = $request->validate([
            'container_no' => 'required|string|max:255',
            'date' => 'required|date',
            'client_id' => 'required|exists:clients,id',
            'shipping_mark_id' => 'nullable|exists:shipping_marks,id',
            'montant_rmb' => 'required|numeric|min:0',
        ]);

        $warehouseFee->update($validated);

        return redirect()->route('warehouse-fees.index')
            ->with('success', '仓库费用更新成功');
    }

    public function destroy(WarehouseFee $warehouseFee)
    {
        $warehouseFee->delete();

        return redirect()->route('warehouse-fees.index')
            ->with('success', '仓库费用删除成功');
    }
}
