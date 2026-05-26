<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

            Commercial Invoice Details

        </h2>

    </x-slot>

    @php

        $total = 0;

    @endphp

    <div class="container mx-auto px-4 py-6">

        <!-- TOP -->
        <div class="flex justify-between items-center mb-6">

            <div>

                <h1 class="text-2xl font-bold text-gray-800">

                    Commercial Invoice #{{ $commercialInvoice->invoice_no }}

                </h1>

                <p class="text-gray-600">

                    Client:
                    {{ $commercialInvoice->client->name }}

                </p>

            </div>

            <div class="flex space-x-2">

                <!-- EXCEL -->
                <a href="{{ route('commercial-invoices.download', $commercialInvoice) }}"
                   class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">

                    Download Excel

                </a>

                <!-- BACK -->
                <a href="{{ route('commercial-invoices.index') }}"
                   class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded">

                    Back

                </a>

            </div>

        </div>

        <!-- MAIN CARD -->
        <div class="bg-white shadow-lg rounded-lg p-8">

            <!-- TITLE -->
            <div class="text-center mb-8">

                <h1 class="text-3xl font-bold">

                    COMMERCIAL INVOICE

                </h1>

            </div>

            <!-- INFO -->
            <div class="grid grid-cols-2 gap-8 mb-8">

                <!-- LEFT -->
                <div>

                    <p class="mb-2">

                        <strong>TO:</strong>

                        {{ $commercialInvoice->client->name }}

                    </p>

                    <p class="mb-2">

                        <strong>ADD:</strong>

                        {{ $commercialInvoice->client->address }}

                    </p>

                    <p class="mb-2">

                        <strong>TEL:</strong>

                        {{ $commercialInvoice->client->phone }}

                    </p>

                </div>

                <!-- RIGHT -->
                <div>

                    <p class="mb-2">

                        <strong>Date:</strong>

                        {{ $commercialInvoice->date }}

                    </p>

                    <p class="mb-2">

                        <strong>Invoice No:</strong>

                        {{ $commercialInvoice->invoice_no }}

                    </p>

                    <p class="mb-2">

                        <strong>Mode Of Delivery:</strong>

                        {{ $commercialInvoice->mode_of_delivery }}

                    </p>

                    <p class="mb-2">

                        <strong>Country Of Origin:</strong>

                        {{ $commercialInvoice->country_of_origin }}

                    </p>

                </div>

            </div>

            <!-- PORTS -->
            <div class="grid grid-cols-2 gap-8 mb-8">

                <div>

                    <strong>PORT OF LOADING:</strong>

                    {{ $commercialInvoice->port_of_loading }}

                </div>

                <div>

                    <strong>PORT OF DISCHARGE:</strong>

                    {{ $commercialInvoice->port_of_discharge }}

                </div>

            </div>

            <!-- TABLE -->
            <div class="overflow-x-auto">

                <table class="w-full border border-gray-400">

                    <thead class="bg-gray-200">

                        <tr>

                            <th class="border p-2">

                                ITEM NO

                            </th>

                            <th class="border p-2">

                                DESCRIPTION

                            </th>

                            <th class="border p-2">

                                CTN

                            </th>

                            <th class="border p-2">

                                PCS/CTS

                            </th>

                            <th class="border p-2">

                                QTY

                            </th>

                            <th class="border p-2">

                                U.PRICE USD

                            </th>

                            <th class="border p-2">

                                AMOUNT USD

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($commercialInvoice->products as $product)

                            @php

                                $qty =
                                    $product->pivot->ctn *
                                    $product->pcs_cts;

                                $amount =
                                    $qty *
                                    $product->pivot->unit_price;

                                $total += $amount;

                            @endphp

                            <tr>

                                <!-- ITEM -->
                                <td class="border p-2 text-center">

                                    {{ $product->reference }}

                                </td>

                                <!-- DESCRIPTION -->
                                <td class="border p-2 text-center">

                                    {{ strtoupper($product->description) }}

                                </td>

                                <!-- CTN -->
                                <td class="border p-2 text-center">

                                    {{ $product->pivot->ctn }}

                                </td>

                                <!-- PCS -->
                                <td class="border p-2 text-center">

                                    {{ $product->pcs_cts }}

                                </td>

                                <!-- QTY -->
                                <td class="border p-2 text-center">

                                    {{ $qty }}

                                </td>

                                <!-- PRICE -->
                                <td class="border p-2 text-center">

                                    ${{ number_format($product->pivot->unit_price, 2) }}

                                </td>

                                <!-- AMOUNT -->
                                <td class="border p-2 text-center font-bold">

                                    ${{ number_format($amount, 2) }}

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                    <!-- TOTAL -->
                    <tfoot>

                        <tr class="bg-gray-100 font-bold">

                            <td colspan="6"
                                class="border p-3 text-center">

                                TOTAL AMOUNT FOB BY USD

                            </td>

                            <td class="border p-3 text-center">

                                ${{ number_format($total, 2) }}

                            </td>

                        </tr>

                    </tfoot>

                </table>

            </div>

            <!-- BANK INFO -->
            <div class="mt-8 space-y-2 font-semibold">

                <p>

                    TERM OF PAYMENT :
                    TRANSFER AFTER RECEIVE GOODS

                </p>

                <p>

                    BENEFICIARY NAME :
                    {{ auth()->user()->company_name }}

                </p>

                <p>

                    A/C NO :
                    NRA3387020511420100045928

                </p>

                <p>

                    SWIFT :
                    ZJCBCN2N

                </p>

                <p>

                    BENEFICIARY BANK :
                    CHINA ZHESHANG BANK YIWU BRANCH

                </p>

                <p>

                    BANK ADDRESS :
                    NO.955,BEICUN ROAD,YIWU,ZHEJIANG,CHINA

                </p>

            </div>

        </div>

    </div>

</x-app-layout>