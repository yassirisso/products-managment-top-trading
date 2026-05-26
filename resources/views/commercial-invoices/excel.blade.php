@php

    $user = auth()->user();

    $startRow = 11;

    $currentRow = $startRow;

    $productCount =
        count($commercialInvoice->products);

@endphp

<table>

    <!-- COMPANY -->
    <tr>

        <td colspan="7"
            style="text-align:center;
                   font-size:20px;
                   font-weight:bold;
                   border:1px solid black;">

            {{ strtoupper($user->company_name) }}

        </td>

    </tr>

    <!-- ADDRESS -->
    <tr>

        <td colspan="7"
            style="text-align:center;
                   font-size:13px;
                   font-weight:bold;
                   border:1px solid black;">

            {{ $user->company_address }}

        </td>

    </tr>

    <!-- PHONE -->
    <tr>

        <td colspan="7"
            style="text-align:center;
                   font-size:13px;
                   font-weight:bold;
                   border:1px solid black;">

            TEL:
            {{ $user->company_phone }}

            FAX:
            {{ $user->company_phone }}

        </td>

    </tr>

    <!-- TITLE -->
    <tr>

        <td colspan="7"
            style="text-align:center;
                   font-size:16px;
                   font-weight:bold;
                   border:1px solid black;">

            COMMERCIAL INVOICE

        </td>

    </tr>

    <!-- CLIENT -->
    <tr>

        <td colspan="4"
            rowspan="3"
            style="border:1px solid black;
                   vertical-align:top;">

            <strong>TO:</strong>
            {{ $commercialInvoice->client->name }}

            <br><br>

            <strong>ADD:</strong>
            {{ $commercialInvoice->client->address }}

            <br><br>

            <strong>TEL:</strong>
            {{ $commercialInvoice->client->phone }}

        </td>

        <td colspan="3"
            rowspan="3"
            style="border:1px solid black;
                   vertical-align:top;">

            <strong>Invoice No:</strong>
            {{ $commercialInvoice->invoice_no }}

            <br><br>

            <strong>Date:</strong>
            {{ $commercialInvoice->date }}

            <br><br>

            <strong>Mode:</strong>
            {{ $commercialInvoice->mode_of_delivery }}

        </td>

    </tr>

    <tr></tr>
    <tr></tr>

    <!-- PORT -->
    <tr>

        <td colspan="4"
            style="border:1px solid black;">

            <strong>PORT OF LOADING:</strong>
            {{ $commercialInvoice->port_of_loading }}

        </td>

        <td colspan="3"
            style="border:1px solid black;">

            <strong>PORT OF DISCHARGE:</strong>
            {{ $commercialInvoice->port_of_discharge }}

        </td>

    </tr>

    <!-- COUNTRY -->
    <tr>

        <td colspan="4"
            style="border:1px solid black;">

            <strong>COUNTRY OF ORIGIN:</strong>
            {{ $commercialInvoice->country_of_origin }}

        </td>

        <td colspan="3"
            style="border:1px solid black;">

            <strong>MODE OF DELIVERY:</strong>
            {{ $commercialInvoice->mode_of_delivery }}

        </td>

    </tr>

    <!-- HEADER -->
    <tr style="font-weight:bold;
               text-align:center;">

        <td style="border:1px solid black;">
            ITEM NO
        </td>

        <td style="border:1px solid black;">
            DESCRIPTION
        </td>

        <td style="border:1px solid black;">
            CTN
        </td>

        <td style="border:1px solid black;">
            PCS/CTS
        </td>

        <td style="border:1px solid black;">
            QTY
        </td>

        <td style="border:1px solid black;">
            U.PRICE USD
        </td>

        <td style="border:1px solid black;">
            AMOUNT USD
        </td>

    </tr>

    @foreach($commercialInvoice->products as $product)

        <tr style="height:60px;
                   text-align:center;
                   vertical-align:middle;">

            <!-- ITEM -->
            <td style="border:1px solid black;">

                {{ $product->reference }}

            </td>

            <!-- DESCRIPTION -->
            <td style="border:1px solid black;">

                {{ strtoupper($product->description) }}

            </td>

            <!-- CTN -->
            <td style="border:1px solid black;">

                {{ $product->pivot->ctn }}

            </td>

            <!-- PCS -->
            <td style="border:1px solid black;">

                {{ $product->pcs_cts }}

            </td>

            <!-- QTY -->
            <td style="border:1px solid black;">

                =C{{ $currentRow }}*D{{ $currentRow }}

            </td>

            <!-- PRICE -->
            <td style="border:1px solid black;">

                {{ $product->pivot->unit_price }}

            </td>

            <!-- AMOUNT -->
            <td style="border:1px solid black;">

                =E{{ $currentRow }}*F{{ $currentRow }}

            </td>

        </tr>

        @php
            $currentRow++;
        @endphp

    @endforeach

    @php

        $endRow =
            $startRow + $productCount - 1;

        $totalRow =
            $endRow + 1;

    @endphp

    <!-- TOTAL -->
    <tr style="font-weight:bold;
            text-align:center;">

        <td colspan="6"
            style="border:1px solid black;">

            TOTAL AMOUNT FOB BY USD

        </td>

        <td style="border:1px solid black;">

            =SUM(G{{ $startRow }}:G{{ $endRow }})

        </td>

    </tr>

    <!-- EMPTY -->
    <tr>
        <td colspan="7"></td>
    </tr>

    <!-- PAYMENT -->
    <tr>

        <td colspan="7"
            style="font-weight:bold;
                border:1px solid black;">

            TERM OF PAYMENT :
            TRANSFER AFTER RECEIVE GOODS

        </td>

    </tr>

    <!-- BENEFICIARY -->
    <tr>

        <td colspan="7"
            style="font-weight:bold;
                border:1px solid black;">

            BENEFICIARY NAME :
            {{ $commercialInvoice->bankAccount?->beneficiary_name }}

        </td>

    </tr>

    <!-- ACCOUNT -->
    <tr>

        <td colspan="7"
            style="font-weight:bold;
                border:1px solid black;">

            A/C NO :
            {{ $commercialInvoice->bankAccount?->account_number }}

        </td>

    </tr>

    <!-- SWIFT -->
    <tr>

        <td colspan="7"
            style="font-weight:bold;
                border:1px solid black;">

            SWIFT :
            {{ $commercialInvoice->bankAccount?->swift }}

        </td>

    </tr>

    <!-- ADDRESS -->
    <tr>

        <td colspan="7"
            style="font-weight:bold;
                border:1px solid black;">

            BANK ADDRESS :
            {{ $commercialInvoice->bankAccount?->bank_address }}
        </td>

    </tr>

</table>