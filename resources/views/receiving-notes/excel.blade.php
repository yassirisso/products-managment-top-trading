<table>
    <!-- COMPANY HEADER -->
    <tr>
        <td colspan="7" style="text-align:center;font-size:20px;font-weight:bold;border:1px solid black;">
            {{ auth()->user()->company_name }}
        </td>
    </tr>
    <tr>
        <td colspan="7" style="text-align:center;font-size:13px;font-weight:bold;border:1px solid black;">
            {{ auth()->user()->company_address }}
        </td>
    </tr>
    <tr>
        <td colspan="7" style="text-align:center;font-size:13px;font-weight:bold;border:1px solid black;">
            TEL: {{ auth()->user()->company_phone }}
            FAX: {{ auth()->user()->company_fax }}
        </td>
    </tr>
    <tr>
        <td colspan="7" style="text-align:center;font-size:16px;font-weight:bold;border:1px solid black;">
            {{ strtoupper(__('messages.receiving_notes')) }}
        </td>
    </tr>

    <!-- CONTAINER INFO -->
    <tr>
        <td colspan="3" style="border:1px solid black;vertical-align:top;padding:5px;">
            <strong>{{ __('messages.container_no') }}:</strong> {{ $receivingNote->container_no ?? '—' }}<br>
            <strong>{{ __('messages.seal_no') }}:</strong> {{ $receivingNote->seal_no ?? '—' }}
        </td>
        <td colspan="4" style="border:1px solid black;vertical-align:top;padding:5px;">
            <strong>{{ __('messages.date') }}:</strong>
            {{ $receivingNote->date ? $receivingNote->date->format('Y-m-d') : '—' }}<br>
            @if ($receivingNote->packingList)
                <strong>{{ __('messages.packing_lists') }}:</strong> #{{ $receivingNote->packingList->id }}
            @endif
        </td>
    </tr>

    <!-- TABLE HEADER -->
    <tr style="font-weight:bold;text-align:center;background-color:#f3f4f6;">
        <td style="border:1px solid black;padding:5px;">{{ __('messages.supplier') }}</td>
        <td style="border:1px solid black;padding:5px;">{{ __('messages.product') }} Count</td>
        <td style="border:1px solid black;padding:5px;">{{ __('messages.ctn') }}</td>
        <td style="border:1px solid black;padding:5px;">{{ __('messages.qty') }}</td>
        <td style="border:1px solid black;padding:5px;">{{ __('messages.total_cbm') }}</td>
        <td style="border:1px solid black;padding:5px;">{{ __('messages.total_gw') }}</td>
        <td style="border:1px solid black;padding:5px;">{{ __('messages.total_nw') }}</td>
    </tr>

    @php
        $totalCtn = 0;
        $totalQty = 0;
        $totalCbm = 0;
        $totalGw = 0;
        $totalNw = 0;
    @endphp

    @foreach ($receivingNote->products->groupBy(fn($p) => $p->suppliers->first()?->id) as $supplierId => $products)
        @php
            $supplier = $products->first()->suppliers->first();
            $sumCtn = $products->sum(fn($p) => $p->pivot->ctn);
            $sumQty = $products->sum(fn($p) => $p->pivot->ctn * ($p->pcs_cts ?? 0));
            $sumCbm = $products->sum(fn($p) => $p->pivot->ctn * ($p->unit_cbm ?? 0));
            $sumGw = $products->sum(fn($p) => $p->pivot->ctn * ($p->unit_gw ?? 0));
            $sumNw = $products->sum(fn($p) => $p->pivot->ctn * ($p->unit_nw ?? 0));

            $totalCtn += $sumCtn;
            $totalQty += $sumQty;
            $totalCbm += $sumCbm;
            $totalGw += $sumGw;
            $totalNw += $sumNw;
        @endphp

        <tr style="text-align:center;">
            <td style="border:1px solid black;padding:5px;text-align:left;">{{ $supplier?->name ?? '—' }}</td>
            <td style="border:1px solid black;padding:5px;">{{ $products->count() }}</td>
            <td style="border:1px solid black;padding:5px;">{{ $sumCtn }}</td>
            <td style="border:1px solid black;padding:5px;">{{ $sumQty }}</td>
            <td style="border:1px solid black;padding:5px;">{{ number_format($sumCbm, 3) }}</td>
            <td style="border:1px solid black;padding:5px;">{{ number_format($sumGw, 2) }}</td>
            <td style="border:1px solid black;padding:5px;">{{ number_format($sumNw, 2) }}</td>
        </tr>
    @endforeach

    <!-- TOTALS -->
    <tr style="font-weight:bold;text-align:center;background-color:#f3f4f6;">
        <td colspan="2" style="border:1px solid black;padding:5px;text-align:right;">{{ __('messages.total') }}</td>
        <td style="border:1px solid black;padding:5px;">{{ $totalCtn }}</td>
        <td style="border:1px solid black;padding:5px;">{{ $totalQty }}</td>
        <td style="border:1px solid black;padding:5px;">{{ number_format($totalCbm, 3) }}</td>
        <td style="border:1px solid black;padding:5px;">{{ number_format($totalGw, 2) }}</td>
        <td style="border:1px solid black;padding:5px;">{{ number_format($totalNw, 2) }}</td>
    </tr>

    @if ($receivingNote->note)
        <tr>
            <td colspan="7" style="border:1px solid black;padding:5px;">
                <strong>{{ __('messages.note') }}:</strong> {{ $receivingNote->note }}
            </td>
        </tr>
    @endif
</table>
