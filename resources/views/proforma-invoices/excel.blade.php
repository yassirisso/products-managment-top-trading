<table>

    <!-- COMPANY HEADER -->
    <tr>

        <td colspan="8"
            style="text-align:center;
                   font-size:20px;
                   font-weight:bold;
                   border:1px solid black;">

            {{ auth()->user()->company_name }}

        </td>

    </tr>

    <!-- ADDRESS -->
    <tr>

        <td colspan="8"
            style="text-align:center;
                   font-size:13px;
                   font-weight:bold;
                   border:1px solid black;">

            {{ auth()->user()->company_address }}

        </td>

    </tr>

    <!-- PHONE -->
    <tr>

        <td colspan="8"
            style="text-align:center;
                   font-size:13px;
                   font-weight:bold;
                   border:1px solid black;">

            TEL:
            {{ auth()->user()->company_phone }}

            FAX:
            {{ auth()->user()->company_phone }}

        </td>

    </tr>

    <!-- TITLE -->
    <tr>

        <td colspan="8"
            style="text-align:center;
                   font-size:16px;
                   font-weight:bold;
                   border:1px solid black;">

            PROFORMA INVOICE

        </td>

    </tr>

    <!-- CLIENT + DATE -->
    <tr>

        <td colspan="5"
            rowspan="3"
            style="border:1px solid black;
                   vertical-align:top;">

            <strong>TO:</strong>
            {{ $proformaInvoice->client->name }}

            <br><br>

            <strong>ADD:</strong>
            {{ $proformaInvoice->client->address }}

            <br><br>

            <strong>TEL:</strong>
            {{ $proformaInvoice->client->phone }}

        </td>

        <td colspan="3"
            rowspan="3"
            style="border:1px solid black;
                   vertical-align:top;">

            <strong>Date:</strong>
            {{ $proformaInvoice->date }}

            <br><br>

            <strong>Container No:</strong>
            {{ $proformaInvoice->container_no }}

        </td>

    </tr>

    <tr></tr>
    <tr></tr>

    <!-- PORT -->
    <tr>

        <td colspan="5"
            style="border:1px solid black">

            <strong>PORT OF LOADING:</strong>
            {{ $proformaInvoice->port_of_loading }}

        </td>

        <td colspan="3"
            style="border:1px solid black">

            <strong>Seal No:</strong>
            {{ $proformaInvoice->seal_no }}

        </td>

    </tr>

    <!-- DISCHARGE -->
    <tr>

        <td colspan="5"
            style="border:1px solid black">

            <strong>PORT OF DISCHARGE:</strong>
            {{ $proformaInvoice->port_of_discharge }}

        </td>

        <td colspan="3"
            style="border:1px solid black"></td>

    </tr>

    <!-- TABLE HEADER -->
    <tr style="font-weight:bold;
               text-align:center;">

        <td style="border:1px solid black;width:15px">
            ITEM NO
        </td>

        <td style="border:1px solid black;width:25px">
            PICTURE
        </td>

        <td style="border:1px solid black;width:40px">
            DESCRIPTION
        </td>

        <td style="border:1px solid black">
            CTN
        </td>

        <td style="border:1px solid black">
            PCS/CTS
        </td>

        <td style="border:1px solid black">
            QTY
        </td>

        <td style="border:1px solid black">
            U.PRICE RMB
        </td>

        <td style="border:1px solid black">
            AMOUNT RMB
        </td>

    </tr>

    @php
        $startRow = 11;

        $currentRow = $startRow;

        $productCount = count($proformaInvoice->products);
    @endphp

    @foreach($proformaInvoice->products as $product)

        <tr style="height:80px;
                   text-align:center;
                   vertical-align:middle;">

            <!-- ITEM -->
            <td style="border:1px solid black">

                {{ $product->reference }}

            </td>

            <!-- IMAGE -->
            <td style="border:1px solid black">

                @if($product->image)

                    <img
                        src="{{ public_path('storage/'.$product->image) }}"
                        width="60"
                        height="60">

                @endif

            </td>

            <!-- DESCRIPTION -->
            <td style="border:1px solid black">

                {{ strtoupper($product->description) }}

            </td>

            <!-- CTN -->
            <td style="border:1px solid black">

                {{ $product->pivot->ctn }}

            </td>

            <!-- PCS -->
            <td style="border:1px solid black">

                {{ $product->pcs_cts }}

            </td>

            <!-- QTY -->
            <td style="border:1px solid black">

                =D{{ $currentRow }}*E{{ $currentRow }}

            </td>

            <!-- UNIT PRICE -->
            <td style="border:1px solid black">

                {{ $product->pivot->unit_price }}

            </td>

            <!-- AMOUNT -->
            <td style="border:1px solid black">

                =F{{ $currentRow }}*G{{ $currentRow }}

            </td>

        </tr>

        @php
            $currentRow++;
        @endphp

    @endforeach

    @php

        $endRow = $startRow + $productCount - 1;

        $totalRow = $endRow + 1;

        $commissionRow = $endRow + 2;

        $localChargeRow = $endRow + 3;

        $fobRow = $endRow + 4;

    @endphp

    <!-- TOTAL EXW -->
    <tr style="font-weight:bold;text-align:center;">

        <td colspan="7"
            style="border:1px solid black">

            TOTAL AMOUNT EXW BY RMB

        </td>

        <td style="border:1px solid black">

            =SUM(H{{ $startRow }}:H{{ $endRow }})

        </td>

    </tr>

    <!-- COMMISSION -->
    <tr style="font-weight:bold;text-align:center;">

        <td colspan="7"
            style="border:1px solid black">

            COMMISSION

        </td>

        <td style="border:1px solid black">

            =H{{ $totalRow }}*3%

        </td>

    </tr>

    <!-- LOCAL -->
    <tr style="font-weight:bold;text-align:center;">

        <td colspan="7"
            style="border:1px solid black">

            LOCAL CHARGE

        </td>

        <td style="border:1px solid black">

            {{ $proformaInvoice->local_charge }}

        </td>

    </tr>

    <!-- FOB -->
    <tr style="font-weight:bold;text-align:center;">

        <td colspan="7"
            style="border:1px solid black">

            TOTAL AMOUNT FOB BY RMB

        </td>

        <td style="border:1px solid black">

            =H{{ $totalRow }}+H{{ $commissionRow }}+H{{ $localChargeRow }}

        </td>

    </tr>

</table>