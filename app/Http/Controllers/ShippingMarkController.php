<?php

namespace App\Http\Controllers;

use App\Models\ShippingMark;
use Illuminate\Http\Request;

class ShippingMarkController extends Controller
{
    public function index()
    {
        $shippingMarks = ShippingMark::latest()->paginate(10);
        return view('shipping-marks.index', compact('shippingMarks'));
    }

    public function create()
    {
        return view('shipping-marks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        ShippingMark::create($validated);

        return redirect()->route('shipping-marks.index')
            ->with('success', '唛头添加成功');
    }

    public function edit(ShippingMark $shippingMark)
    {
        return view('shipping-marks.edit', compact('shippingMark'));
    }

    public function update(Request $request, ShippingMark $shippingMark)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $shippingMark->update($validated);

        return redirect()->route('shipping-marks.index')
            ->with('success', '唛头更新成功');
    }

    public function destroy(ShippingMark $shippingMark)
    {
        $shippingMark->delete();

        return redirect()->route('shipping-marks.index')
            ->with('success', '唛头删除成功');
    }
}