<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
            {{ __('messages.packing_list_details') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
            <a href="{{ route('packing-lists.download', $packingList->id) }}"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-center text-sm w-full sm:w-auto">
                {{ __('messages.download_excel') }}
            </a>
            <a href="{{ route('packing-lists.index') }}"
                class="text-gray-600 hover:text-gray-800 text-sm text-center sm:text-right">
                &larr; {{ __('messages.back_to_packing_lists') }}
            </a>
        </div>

        <!-- HEADER -->
        <div class="bg-white shadow-lg rounded-lg p-4 md:p-8">
            <div class="text-center mb-6 md:mb-8">
                <h1 class="text-2xl md:text-3xl font-bold">
                    {{ __('messages.packing_lists_management') }}
                </h1>
            </div>

            <!-- CLIENT + SHIPPING -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 mb-8">
                <div class="min-w-0 text-sm md:text-base">
                    <h2 class="font-bold text-base md:text-lg mb-3">{{ __('messages.client_info') }}</h2>
                    <p class="break-words"><strong>TO:</strong> {{ $packingList->client->name }}</p>
                    <p class="break-words"><strong>ADD:</strong> {{ $packingList->client->address }}</p>
                    <p class="break-words"><strong>TEL:</strong> {{ $packingList->client->phone }}</p>
                </div>

                <div class="min-w-0 text-sm md:text-base">
                    <h2 class="font-bold text-base md:text-lg mb-3">{{ __('messages.shipping_info') }}</h2>
                    <p class="break-words"><strong>{{ __('messages.port_of_loading') }}:</strong>
                        {{ $packingList->port_of_loading }}</p>
                    <p class="break-words"><strong>{{ __('messages.port_of_discharge') }}:</strong>
                        {{ $packingList->port_of_discharge }}</p>
                    <p><strong>{{ __('messages.date') }}:</strong> {{ $packingList->date }}</p>
                    <p class="break-words"><strong>{{ __('messages.container_no') }}:</strong>
                        {{ $packingList->container_no }}</p>
                    <p class="break-words"><strong>{{ __('messages.seal_no') }}:</strong> {{ $packingList->seal_no }}
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
                                <p class="font-semibold text-gray-900 break-words">{{ $product->reference }}</p>
                                <p class="text-gray-600 break-words">{{ $product->description }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 mt-3 text-gray-700">
                            <p><span class="text-gray-500">{{ __('messages.ctn') }}:</span> {{ $product->pivot->ctn }}
                            </p>
                            <p><span class="text-gray-500">{{ __('messages.pcs_cts') }}:</span>
                                {{ $product->pcs_cts }}</p>
                            <p><span class="text-gray-500">{{ __('messages.qty') }}:</span> {{ $rowQty }}</p>
                            <p><span class="text-gray-500">CBM:</span> {{ number_format($rowCbm, 3) }}</p>
                            <p><span class="text-gray-500">GW:</span> {{ number_format($rowGw, 2) }}</p>
                            <p><span class="text-gray-500">NW:</span> {{ number_format($rowNw, 2) }}</p>
                        </div>
                    </div>
                @endforeach

                <!-- TOTALS CARD -->
                <div class="bg-gray-100 rounded-lg p-3 text-sm font-semibold text-gray-800">
                    <p class="mb-2">{{ __('messages.total') }}</p>
                    <div class="grid grid-cols-2 gap-2">
                        <p><span class="text-gray-500 font-normal">{{ __('messages.ctn') }}:</span>
                            {{ $totalCtn }}</p>
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
                            <th class="border p-2">{{ __('messages.image') }}</th>
                            <th class="border p-2">ITEM NO</th>
                            <th class="border p-2">{{ __('messages.description') }}</th>
                            <th class="border p-2">{{ __('messages.ctn') }}</th>
                            <th class="border p-2">{{ __('messages.pcs_cts') }}</th>
                            <th class="border p-2">{{ __('messages.qty') }}</th>
                            <th class="border p-2">{{ __('messages.total_cbm') }}</th>
                            <th class="border p-2">{{ __('messages.total_gw') }}</th>
                            <th class="border p-2">{{ __('messages.total_nw') }}</th>
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
                                <td class="border p-2 text-center">{{ $product->reference }}</td>
                                <td class="border p-2">{{ $product->description }}</td>
                                <td class="border p-2 text-center">{{ $product->pivot->ctn }}</td>
                                <td class="border p-2 text-center">{{ $product->pcs_cts }}</td>
                                <td class="border p-2 text-center">{{ $rowQty }}</td>
                                <td class="border p-2 text-center">{{ number_format($rowCbm, 3) }}</td>
                                <td class="border p-2 text-center">{{ number_format($rowGw, 2) }}</td>
                                <td class="border p-2 text-center">{{ number_format($rowNw, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-100 font-bold">
                        <tr>
                            <td colspan="3" class="border p-2 text-right">{{ __('messages.total') }}</td>
                            <td class="border p-2 text-center">{{ $totalCtn }}</td>
                            <td class="border p-2"></td>
                            <td class="border p-2"></td>
                            <td class="border p-2 text-center">{{ number_format($totalCbm, 3) }}</td>
                            <td class="border p-2 text-center">{{ number_format($totalGw, 2) }}</td>
                            <td class="border p-2 text-center">{{ number_format($totalNw, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
