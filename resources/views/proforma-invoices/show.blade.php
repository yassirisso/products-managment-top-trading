<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

            Proforma Invoice Details

        </h2>

    </x-slot>

    @php

        $total = 0;

    @endphp

    <div class="container mx-auto px-4 py-6">

        <!-- TOP BUTTONS -->
        <div class="flex justify-between items-center mb-6">

            <div>

                <h1 class="text-2xl font-bold text-gray-800">

                    Proforma Invoice #{{ $proformaInvoice->id }}

                </h1>

                <p class="text-gray-600">

                    Client: {{ $proformaInvoice->client->name }}

                </p>

            </div>

            <div class="flex items-center space-x-2">

                <!-- DOWNLOAD -->
                <a href="{{ route('proforma-invoices.download', $proformaInvoice->id) }}"
                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">

                    Download Excel

                </a>

                <!-- BACK -->
                <a href="{{ route('proforma-invoices.index') }}"
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">

                    Back

                </a>

            </div>

        </div>

        <!-- MAIN CARD -->
        <div class="bg-white shadow-lg rounded-lg p-8">

            <!-- TITLE -->
            <div class="text-center mb-8">

                <h1 class="text-3xl font-bold">

                    PROFORMA INVOICE

                </h1>

            </div>

            <!-- CLIENT + SHIPPING -->
            <div class="grid grid-cols-2 gap-8 mb-8">

                <!-- CLIENT -->
                <div>

                    <h2 class="font-bold text-lg mb-4">

                        CLIENT INFO

                    </h2>

                    <p class="mb-2">

                        <strong>TO:</strong>

                        {{ $proformaInvoice->client->name }}

                    </p>

                    <p class="mb-2">

                        <strong>ADD:</strong>

                        {{ $proformaInvoice->client->address }}

                    </p>

                    <p class="mb-2">

                        <strong>TEL:</strong>

                        {{ $proformaInvoice->client->phone }}

                    </p>

                </div>

                <!-- SHIPPING -->
                <div>

                    <h2 class="font-bold text-lg mb-4">

                        SHIPPING INFO

                    </h2>

                    <p class="mb-2">

                        <strong>PORT OF LOADING:</strong>

                        {{ $proformaInvoice->port_of_loading }}

                    </p>

                    <p class="mb-2">

                        <strong>PORT OF DISCHARGE:</strong>

                        {{ $proformaInvoice->port_of_discharge }}

                    </p>

                    <p class="mb-2">

                        <strong>DATE:</strong>

                        {{ $proformaInvoice->date }}

                    </p>

                    <p class="mb-2">

                        <strong>CONTAINER NO:</strong>

                        {{ $proformaInvoice->container_no }}

                    </p>

                    <p class="mb-2">

                        <strong>SEAL NO:</strong>

                        {{ $proformaInvoice->seal_no }}

                    </p>

                </div>

            </div>

            <!-- PRODUCTS TABLE -->
            <div class="overflow-x-auto">

                <table class="w-full border border-gray-400">

                    <thead class="bg-gray-200">

                        <tr>

                            <th class="border p-2">

                                IMAGE

                            </th>

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

                                UNIT PRICE

                            </th>

                            <th class="border p-2">

                                AMOUNT

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($proformaInvoice->products as $product)

                            @php

                                $qty = $product->pivot->ctn * $product->pcs_cts;

                                $amount = $qty * $product->pivot->unit_price;

                                $total += $amount;

                            @endphp

                            <tr>

                                <!-- IMAGE -->
                                <td class="border p-2 text-center">

                                    @if($product->image)

                                        <img
                                            src="{{ asset('storage/' . $product->image) }}"
                                            alt="{{ $product->reference }}"
                                            class="w-24 h-24 object-cover rounded mx-auto border"
                                        >

                                    @else

                                        <div class="w-24 h-24 bg-gray-100 flex items-center justify-center text-gray-400 text-xs mx-auto rounded border">

                                            NO IMAGE

                                        </div>

                                    @endif

                                </td>

                                <!-- ITEM -->
                                <td class="border p-2 text-center">

                                    {{ $product->reference }}

                                </td>

                                <!-- DESCRIPTION -->
                                <td class="border p-2">

                                    {{ $product->description }}

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

                                <!-- UNIT PRICE -->
                                <td class="border p-2 text-center">

                                    ¥{{ number_format($product->pivot->unit_price, 2) }}

                                </td>

                                <!-- AMOUNT -->
                                <td class="border p-2 text-center font-bold">

                                    ¥{{ number_format($amount, 2) }}

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                    <!-- TOTALS -->
                    <tfoot class="bg-gray-100 font-bold">

                        <!-- TOTAL EXW -->
                        <tr>

                            <td colspan="7"
                                class="border p-2 text-right">

                                TOTAL EXW

                            </td>

                            <td class="border p-2 text-center">

                                ¥{{ number_format($total, 2) }}

                            </td>

                        </tr>

                        <!-- COMMISSION -->
                        <tr>

                            <td colspan="7"
                                class="border p-2 text-right">

                                COMMISSION (3%)

                            </td>

                            <td class="border p-2 text-center">

                                ¥{{ number_format($total * 0.03, 2) }}

                            </td>

                        </tr>

                        <!-- LOCAL CHARGE -->
                        <tr>

                            <td colspan="7"
                                class="border p-2 text-right">

                                LOCAL CHARGE

                            </td>

                            <td class="border p-2 text-center">

                                ¥{{ number_format($proformaInvoice->local_charge, 2) }}

                            </td>

                        </tr>

                        <!-- FOB TOTAL -->
                        <tr class="bg-yellow-100">

                            <td colspan="7"
                                class="border p-3 text-right text-lg">

                                FOB TOTAL

                            </td>

                            <td class="border p-3 text-center text-lg">

                                ¥{{ number_format(
                                    $total +
                                    ($total * 0.03) +
                                    $proformaInvoice->local_charge,
                                    2
                                ) }}

                            </td>

                        </tr>

                    </tfoot>

                </table>

            </div>

        </div>

    </div>

</x-app-layout>