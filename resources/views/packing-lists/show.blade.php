<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

            Packing List Details

        </h2>

    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <div class="mb-6">

            <a href="{{ route('packing-lists.download', $packingList->id) }}"
               class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">

                Download Excel

            </a>

        </div>

        <!-- HEADER -->
        <div class="bg-white shadow-lg rounded-lg p-8">

            <!-- TOP -->
            <div class="text-center mb-8">

                <h1 class="text-3xl font-bold">

                    PACKING LIST

                </h1>

            </div>

            <!-- CLIENT + SHIPPING -->
            <div class="grid grid-cols-2 gap-8 mb-8">

                <!-- CLIENT -->
                <div>

                    <h2 class="font-bold text-lg mb-4">

                        CLIENT INFO

                    </h2>

                    <p>

                        <strong>TO:</strong>

                        {{ $packingList->client->name }}

                    </p>

                    <p>

                        <strong>ADD:</strong>

                        {{ $packingList->client->address }}

                    </p>

                    <p>

                        <strong>TEL:</strong>

                        {{ $packingList->client->phone }}

                    </p>

                </div>

                <!-- SHIPPING -->
                <div>

                    <h2 class="font-bold text-lg mb-4">

                        SHIPPING INFO

                    </h2>

                    <p>

                        <strong>PORT OF LOADING:</strong>

                        {{ $packingList->port_of_loading }}

                    </p>

                    <p>

                        <strong>PORT OF DISCHARGE:</strong>

                        {{ $packingList->port_of_discharge }}

                    </p>

                    <p>

                        <strong>DATE:</strong>

                        {{ $packingList->date }}

                    </p>

                    <p>

                        <strong>CONTAINER NO:</strong>

                        {{ $packingList->container_no }}

                    </p>

                    <p>

                        <strong>SEAL NO:</strong>

                        {{ $packingList->seal_no }}

                    </p>

                </div>

            </div>

            <!-- PRODUCTS TABLE -->
            <table class="w-full border border-gray-400">

                @php

                    $totalCtn = 0;
                    $totalCbm = 0;
                    $totalGw = 0;
                    $totalNw = 0;

                @endphp

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

                            TOTAL CBM

                        </th>

                        <th class="border p-2">

                            TOTAL GW

                        </th>

                        <th class="border p-2">

                            TOTAL NW

                        </th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($packingList->products as $product)

                        @php

                            $rowQty = $product->pivot->ctn * $product->pcs_cts;

                            $rowCbm = $product->pivot->ctn * $product->unit_cbm;

                            $rowGw = $product->pivot->ctn * $product->unit_gw;

                            $rowNw = $product->pivot->ctn * $product->unit_nw;

                            $totalCtn += $product->pivot->ctn;

                            $totalCbm += $rowCbm;

                            $totalGw += $rowGw;

                            $totalNw += $rowNw;

                        @endphp

                        <tr>

                            <!-- IMAGE -->
                            <td class="border p-2">

                                @if($product->image)

                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         class="w-20 h-20 object-cover mx-auto">

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

                                {{ $rowQty }}

                            </td>

                            <!-- TOTAL CBM -->
                            <td class="border p-2 text-center">

                                {{ number_format($rowCbm, 3) }}

                            </td>

                            <!-- TOTAL GW -->
                            <td class="border p-2 text-center">

                                {{ number_format($rowGw, 2) }}

                            </td>

                            <!-- TOTAL NW -->
                            <td class="border p-2 text-center">

                                {{ number_format($rowNw, 2) }}

                            </td>

                        </tr>

                    @endforeach

                </tbody>

                <tfoot class="bg-gray-100 font-bold">

                    <tr>

                        <td colspan="3"
                            class="border p-2 text-right">

                            TOTAL

                        </td>

                        <!-- TOTAL CTN -->
                        <td class="border p-2 text-center">

                            {{ $totalCtn }}

                        </td>

                        <!-- PCS -->
                        <td class="border p-2">

                        </td>

                        <!-- QTY -->
                        <td class="border p-2">

                        </td>

                        <!-- TOTAL CBM -->
                        <td class="border p-2 text-center">

                            {{ number_format($totalCbm, 3) }}

                        </td>

                        <!-- TOTAL GW -->
                        <td class="border p-2 text-center">

                            {{ number_format($totalGw, 2) }}

                        </td>

                        <!-- TOTAL NW -->
                        <td class="border p-2 text-center">

                            {{ number_format($totalNw, 2) }}

                        </td>

                    </tr>

                </tfoot>

            </table>

        </div>

    </div>

</x-app-layout>