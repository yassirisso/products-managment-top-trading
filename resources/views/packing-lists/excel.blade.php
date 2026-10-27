<table>
    <!-- COMPANY HEADER -->
    <tr>
        <td colspan="9" style="text-align:center;font-size:20px;font-weight:bold;border:1px solid black;">
            {{ auth()->user()->company_name }}
        </td>
    </tr>
    <tr>
        <td colspan="9" style="text-align:center;font-size:13px;font-weight:bold;border:1px solid black;">
            {{ auth()->user()->company_address }}
        </td>
    </tr>
    <tr>
        <td colspan="9" style="text-align:center;font-size:13px;font-weight:bold;border:1px solid black;">
            TEL: {{ auth()->user()->company_phone }}
            FAX: {{ auth()->user()->company_fax }}
        </td>
    </tr>
    <tr>
        <td colspan="9" style="text-align:center;font-size:16px;font-weight:bold;border:1px solid black;">
            {{ strtoupper(__('messages.packing_lists_management')) }}
        </td>
    </tr>

    <!-- CLIENT + DATE -->
    <tr>
        <td colspan="6" rowspan="3" style="border:1px solid black;vertical-align:top;">
            <strong>TO:</strong> {{ $packingList->client->name }}
            <br><br>
            <strong>ADD:</strong> {{ $packingList->client->address }}
            <br><br>
            <strong>TEL:</strong> {{ $packingList->client->phone }}
        </td>
        <td colspan="3" rowspan="3" style="border:1px solid black;vertical-align:top;">
            <strong>{{ __('messages.date') }}:</strong> {{ $packingList->date }}
            <br><br>
            <strong>{{ __('messages.container_no') }}:</strong> {{ $packingList->container_no }}
        </td>
    </tr>
    <tr></tr>
    <tr></tr>

    <!-- PORTS -->
    <tr>
        <td colspan="6" style="border:1px solid black">
            <strong>{{ __('messages.port_of_loading') }}:</strong> {{ $packingList->port_of_loading }}
        </td>
        <td colspan="3" style="border:1px solid black">
            <strong>{{ __('messages.seal_no') }}:</strong> {{ $packingList->seal_no }}
        </td>
    </tr>
    <tr>
        <td colspan="6" style="border:1px solid black">
            <strong>{{ __('messages.port_of_discharge') }}:</strong> {{ $packingList->port_of_discharge }}
        </td>
        <td colspan="3" style="border:1px solid black"></td>
    </tr>

    <!-- TABLE HEADER -->
    <tr style="font-weight:bold;text-align:center;">
        <td style="border:1px solid black;width:15px">ITEM NO</td>
        <td style="border:1px solid black;width:25px">PICTURE</td>
        <td style="border:1px solid black;width:40px">{{ strtoupper(__('messages.description')) }}</td>
        <td style="border:1px solid black">{{ __('messages.ctn') }}</td>
        <td style="border:1px solid black">{{ __('messages.pcs_cts') }}</td>
        <td style="border:1px solid black">{{ __('messages.qty') }}</td>
        <td style="border:1px solid black">{{ __('messages.total_cbm') }}</td>
        <td style="border:1px solid black">{{ __('messages.total_gw') }}</td>
        <td style="border:1px solid black">{{ __('messages.total_nw') }}</td>
    </tr>

    @php
        $startRow = 11;
        $currentRow = $startRow;
    @endphp

    @foreach ($packingList->products as $product)
        @php
            $rowCbm = $product->pivot->ctn * $product->unit_cbm;
            $rowGw = $product->pivot->ctn * $product->unit_gw;
            $rowNw = $product->pivot->ctn * $product->unit_nw;
        @endphp
        <tr style="height:80px;text-align:center;vertical-align:middle;">
            <td style="border:1px solid black">{{ $product->reference }}</td>
            <td style="border:1px solid black">
                @if ($product->image)
                    <img src="{{ public_path('storage/' . $product->image) }}" width="60" height="60">
                @endif
            </td>
            <td style="border:1px solid black">{{ strtoupper($product->description) }}</td>
            <td style="border:1px solid black">{{ $product->pivot->ctn }}</td>
            <td style="border:1px solid black">{{ $product->pcs_cts }}</td>
            <td style="border:1px solid black">=D{{ $currentRow }}*E{{ $currentRow }}</td>
            <td style="border:1px solid black">{{ number_format($rowCbm, 3) }}</td>
            <td style="border:1px solid black">{{ number_format($rowGw, 2) }}</td>
            <td style="border:1px solid black">{{ number_format($rowNw, 2) }}</td>
        </tr>
        @php $currentRow++; @endphp
    @endforeach

    @php $endRow = $currentRow - 1; @endphp

    <!-- TOTAL -->
    <tr style="font-weight:bold;text-align:center;">
        <td colspan="3" style="border:1px solid black">{{ strtoupper(__('messages.total')) }}</td>
        <td style="border:1px solid black">=SUM(D{{ $startRow }}:D{{ $endRow }})</td>
        <td style="border:1px solid black"></td>
        <td style="border:1px solid black">=SUM(F{{ $startRow }}:F{{ $endRow }})</td>
        <td style="border:1px solid black">=SUM(G{{ $startRow }}:G{{ $endRow }})</td>
        <td style="border:1px solid black">=SUM(H{{ $startRow }}:H{{ $endRow }})</td>
        <td style="border:1px solid black">=SUM(I{{ $startRow }}:I{{ $endRow }})</td>
    </tr>

    <!-- BOTTOM SUMMARY -->
    <tr>
        <td colspan="2" style="border:1px solid black">Total Carton:</td>
        <td style="border:1px solid black">=SUM(D{{ $startRow }}:D{{ $endRow }})</td>
        <td colspan="6"></td>
    </tr>
    <tr>
        <td colspan="2" style="border:1px solid black">Gross Weight:</td>
        <td style="border:1px solid black">=SUM(H{{ $startRow }}:H{{ $endRow }})</td>
        <td colspan="6"></td>
    </tr>
    <tr>
        <td colspan="2" style="border:1px solid black">Net Weight:</td>
        <td style="border:1px solid black">=SUM(I{{ $startRow }}:I{{ $endRow }})</td>
        <td colspan="6"></td>
    </tr>
    <tr>
        <td colspan="2" style="border:1px solid black">CBM:</td>
        <td style="border:1px solid black">=SUM(G{{ $startRow }}:G{{ $endRow }})</td>
        <td colspan="6"></td>
    </tr>
</table>
