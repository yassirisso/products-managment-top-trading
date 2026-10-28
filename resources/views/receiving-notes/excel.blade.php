<table>
    <!-- COMPANY HEADER -->
    <tr>
        <td colspan="8" style="text-align:center;font-size:20px;font-weight:bold;border:1px solid black;">
            {{ auth()->user()->company_name }}
        </td>
    </tr>
    <tr>
        <td colspan="8" style="text-align:center;font-size:13px;font-weight:bold;border:1px solid black;">
            {{ auth()->user()->company_address }}
        </td>
    </tr>
    <tr>
        <td colspan="8" style="text-align:center;font-size:13px;font-weight:bold;border:1px solid black;">
            TEL: {{ auth()->user()->company_phone }}
            FAX: {{ auth()->user()->company_fax }}
        </td>
    </tr>
    <tr>
        <td colspan="8" style="text-align:center;font-size:16px;font-weight:bold;border:1px solid black;">
            {{ strtoupper(__('messages.receiving_notes')) }}
        </td>
    </tr>

    <!-- CONTAINER INFO -->
    <tr>
        <td colspan="4" style="border:1px solid black;vertical-align:top;padding:5px;">
            <strong>{{ __('messages.container_no') }}:</strong> {{ $receivingNote->container_no ?? '—' }}<br>
            <strong>{{ __('messages.seal_no') }}:</strong> {{ $receivingNote->seal_no ?? '—' }}
        </td>
        <td colspan="4" style="border:1px solid black;vertical-align:top;padding:5px;">
            <strong>{{ __('messages.date') }}:</strong>
            {{ $receivingNote->date ? $receivingNote->date->format('Y-m-d') : '—' }}<br>
            @if ($receivingNote->proformaInvoice)
                <strong>{{ __('messages.proforma_invoices') }}:</strong> #{{ $receivingNote->proformaInvoice->id }}
            @endif
        </td>
    </tr>

    <!-- TABLE HEADER -->
    <tr style="font-weight:bold;text-align:center;background-color:#f3f4f6;">
        <td style="border:1px solid black;padding:5px;">{{ __('messages.product') }}</td>
        <td style="border:1px solid black;padding:5px;">{{ __('messages.supplier') }}</td>
        <td style="border:1px solid black;padding:5px;">{{ __('messages.ctn') }}</td>
        <td style="border:1px solid black;padding:5px;">{{ __('messages.pcs_cts') }}</td>
        <td style="border:1px solid black;padding:5px;">{{ __('messages.qty') }}</td>
        <td style="border:1px solid black;padding:5px;">{{ __('messages.total_cbm') }}</td>
        <td style="border:1px solid black;padding:5px;">{{ __('messages.total_gw') }}</td>
        <td style="border:1px solid black;padding:5px;">{{ __('messages.total_nw') }}</td>
    </tr>

    <!-- PRODUCTS -->
    @php
        $startRow = 8;
        $currentRow = $startRow;

        $totalCtn = 0;
        $totalQty = 0;
        $totalCbm = 0;
        $totalGw = 0;
        $totalNw = 0;
    @endphp

    @foreach ($receivingNote->products as $product)
        @php
            $qty = $product->pivot->ctn * ($product->pcs_cts ?? 0);
            $cbm = $product->pivot->ctn * ($product->unit_cbm ?? 0);
            $gw = $product->pivot->ctn * ($product->unit_gw ?? 0);
            $nw = $product->pivot->ctn * ($product->unit_nw ?? 0);

            $totalCtn += $product->pivot->ctn;
            $totalQty += $qty;
            $totalCbm += $cbm;
            $totalGw += $gw;
            $totalNw += $nw;
        @endphp

        <tr style="text-align:center;">
            <td style="border:1px solid black;padding:5px;">{{ $product->reference }}</td>
            <td style="border:1px solid black;padding:5px;">{{ $product->suppliers->first()?->name ?? '—' }}</td>
            <td style="border:1px solid black;padding:5px;">{{ $product->pivot->ctn }}</td>
            <td style="border:1px solid black;padding:5px;">{{ $product->pcs_cts }}</td>
            <td style="border:1px solid black;padding:5px;">{{ $qty }}</td>
            <td style="border:1px solid black;padding:5px;">{{ number_format($cbm, 3) }}</td>
            <td style="border:1px solid black;padding:5px;">{{ number_format($gw, 2) }}</td>
            <td style="border:1px solid black;padding:5px;">{{ number_format($nw, 2) }}</td>
        </tr>

        @php $currentRow++; @endphp
    @endforeach

    <!-- TOTALS -->
    <tr style="font-weight:bold;text-align:center;background-color:#f3f4f6;">
        <td colspan="2" style="border:1px solid black;padding:5px;text-align:right;">{{ __('messages.total') }}</td>
        <td style="border:1px solid black;padding:5px;">{{ $totalCtn }}</td>
        <td style="border:1px solid black;padding:5px;"></td>
        <td style="border:1px solid black;padding:5px;">{{ $totalQty }}</td>
        <td style="border:1px solid black;padding:5px;">{{ number_format($totalCbm, 3) }}</td>
        <td style="border:1px solid black;padding:5px;">{{ number_format($totalGw, 2) }}</td>
        <td style="border:1px solid black;padding:5px;">{{ number_format($totalNw, 2) }}</td>
    </tr>

    @if ($receivingNote->note)
        <tr>
            <td colspan="8" style="border:1px solid black;padding:5px;">
                <strong>{{ __('messages.note') }}:</strong> {{ $receivingNote->note }}
            </td>
        </tr>
    @endif
</table>
