<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">

            Packing List Details

        </h2>

    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">

            <a href="{{ route('packing-lists.download', $packingList->id) }}"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-center text-sm w-full sm:w-auto">

                Download Excel

            </a>

            <a href="{{ route('packing-lists.index') }}"
                class="text-gray-600 hover:text-gray-800 text-sm text-center sm:text-right">

                &larr; Back to Packing Lists

            </a>

        </div>

        <!-- HEADER -->
        <div class="bg-white shadow-lg rounded-lg p-4 md:p-8">

            <!-- TOP -->
            <div class="text-center mb-6 md:mb-8">

                <h1 class="text-2xl md:text-3xl font-bold">

                    PACKING LIST

                </h1>

            </div>

            <!-- CLIENT + SHIPPING -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 mb-8">

                <!-- CLIENT -->
                <div class="min-w-0 text-sm md:text-base">

                    <h2 class="font-bold text-base md:text-lg mb-3">

                        CLIENT INFO

                    </h2>

                    <p class="break-words">

                        <strong>TO:</strong>

                        {{ $packingList->client->name }}

                    </p>

                    <p class="break-words">

                        <strong>ADD:</strong>

                        {{ $packingList->client->address }}

                    </p>

                    <p class="break-words">

                        <strong>TEL:</strong>

                        {{ $packingList->client->phone }}

                    </p>

                </div>

                <!-- SHIPPING -->
                <div class="min-w-0 text-sm md:text-base">

                    <h2 class="font-bold text-base md:text-lg mb-3">

                        SHIPPING INFO

                    </h2>

                    <p class="break-words">

                        <strong>PORT OF LOADING:</strong>

                        {{ $packingList->port_of_loading }}

                    </p>

                    <p class="break-words">

                        <strong>PORT OF DISCHARGE:</strong>

                        {{ $packingList->port_of_discharge }}

                    </p>

                    <p>

                        <strong>DATE:</strong>

                        {{ $packingList->date }}

                    </p>

                    <p class="break-words">

                        <strong>CONTAINER NO:</strong>

                        {{ $packingList->container_no }}

                    </p>

                    <p class="break-words">

                        <strong>SEAL NO:</strong>

                        {{ $packingList->seal_no }}

                    </p>

                </div>

            </div>

            @php

                $totalCtn = $packingList->products->sum(fn($p) => $p->pivot->ctn);

                $totalCbm = $packingList->products->sum(fn($p) => $p->pivot->ctn * $p->unit_cbm);

                $totalGw = $packingList->products->sum(fn($p) => $p->pivot->ctn * $p->unit_gw);

                $totalNw = $packingList->products->sum(fn($p) => $p->pivot->ctn * $p->unit_nw);

            @endphp

            <!-- MOBILE / TABLET : PRODUCT CARDS -->
            <div class="lg:hidden space-y-3">

                @foreach ($packingList->products as $product)
                    @php

                        $rowQty = ($product->pivot->ctn ?? 0) * ($product->pcs_cts ?? 0);

                        $rowCbm = ($product->pivot->ctn ?? 0) * ($product->unit_cbm ?? 0);

                        $rowGw = ($product->pivot->ctn ?? 0) * ($product->unit_gw ?? 0);

                        $rowNw = ($product->pivot->ctn ?? 0) * ($product->unit_nw ?? 0);

                    @endphp

                    <div class="border rounded-lg p-3 text-sm">

                        <div class="flex gap-3">

                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}"
                                    class="w-16 h-16 object-cover rounded shrink-0">
                            @endif

                            <div class="min-w-0 flex-1">

                                <p class="font-semibold text-gray-900 break-words">

                                    {{ $product->reference }}

                                </p>

                                <p class="text-gray-600 break-words">

                                    {{ $product->description }}

                                </p>

                            </div>

                        </div>

                        <div class="grid grid-cols-2 gap-2 mt-3 text-gray-700">

                            <p><span class="text-gray-500">CTN:</span> {{ $product->pivot->ctn }}</p>
                            <p><span class="text-gray-500">PCS/CTS:</span> {{ $product->pcs_cts }}</p>
                            <p><span class="text-gray-500">QTY:</span> {{ $rowQty }}</p>
                            <p><span class="text-gray-500">CBM:</span> {{ number_format($rowCbm, 3) }}</p>
                            <p><span class="text-gray-500">GW:</span> {{ number_format($rowGw, 2) }}</p>
                            <p><span class="text-gray-500">NW:</span> {{ number_format($rowNw, 2) }}</p>

                        </div>

                    </div>
                @endforeach

                <!-- TOTALS CARD -->
                <div class="bg-gray-100 rounded-lg p-3 text-sm font-semibold text-gray-800">

                    <p class="mb-2">TOTALS</p>

                    <div class="grid grid-cols-2 gap-2">

                        <p><span class="text-gray-500 font-normal">CTN:</span> {{ $totalCtn }}</p>
                        <p><span class="text-gray-500 font-normal">CBM:</span> {{ number_format($totalCbm, 3) }}</p>
                        <p><span class="text-gray-500 font-normal">GW:</span> {{ number_format($totalGw, 2) }}</p>
                        <p><span class="text-gray-500 font-normal">NW:</span> {{ number_format($totalNw, 2) }}</p>

                    </div>

                </div>

            </div>

            <!-- DESKTOP : TABLE -->
            <div class="hidden lg:block overflow-x-auto">

                <table class="w-full border border-gray-400">

                    <thead class="bg-gray-200">

                        <tr>

                            <th class="border p-2">IMAGE</th>
                            <th class="border p-2">ITEM NO</th>
                            <th class="border p-2">DESCRIPTION</th>
                            <th class="border p-2">CTN</th>
                            <th class="border p-2">PCS/CTS</th>
                            <th class="border p-2">QTY</th>
                            <th class="border p-2">TOTAL CBM</th>
                            <th class="border p-2">TOTAL GW</th>
                            <th class="border p-2">TOTAL NW</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($packingList->products as $product)
                            @php

                                $rowQty = ($product->pivot->ctn ?? 0) * ($product->pcs_cts ?? 0);

                                $rowCbm = ($product->pivot->ctn ?? 0) * ($product->unit_cbm ?? 0);

                                $rowGw = ($product->pivot->ctn ?? 0) * ($product->unit_gw ?? 0);

                                $rowNw = ($product->pivot->ctn ?? 0) * ($product->unit_nw ?? 0);

                            @endphp

                            <tr>

                                <td class="border p-2">

                                    @if ($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                            class="w-20 h-20 object-cover mx-auto">
                                    @endif

                                </td>

                                <td class="border p-2 text-center">
                                    {{ $product->reference }}
                                </td>

                                <td class="border p-2">
                                    {{ $product->description }}
                                </td>

                                <td class="border p-2 text-center">
                                    {{ $product->pivot->ctn }}
                                </td>

                                <td class="border p-2 text-center">
                                    {{ $product->pcs_cts }}
                                </td>

                                <td class="border p-2 text-center">
                                    {{ $rowQty }}
                                </td>

                                <td class="border p-2 text-center">
                                    {{ number_format($rowCbm, 3) }}
                                </td>

                                <td class="border p-2 text-center">
                                    {{ number_format($rowGw, 2) }}
                                </td>

                                <td class="border p-2 text-center">
                                    {{ number_format($rowNw, 2) }}
                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                    <tfoot class="bg-gray-100 font-bold">

                        <tr>

                            <td colspan="3" class="border p-2 text-right">
                                TOTAL
                            </td>

                            <td class="border p-2 text-center">
                                {{ $totalCtn }}
                            </td>

                            <td class="border p-2"></td>

                            <td class="border p-2"></td>

                            <td class="border p-2 text-center">
                                {{ number_format($totalCbm, 3) }}
                            </td>

                            <td class="border p-2 text-center">
                                {{ number_format($totalGw, 2) }}
                            </td>

                            <td class="border p-2 text-center">
                                {{ number_format($totalNw, 2) }}
                            </td>

                        </tr>

                    </tfoot>

                </table>

            </div>

        </div>

    </div>

</x-app-layout>
