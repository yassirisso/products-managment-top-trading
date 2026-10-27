<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
            {{ __('messages.proforma_invoice_details') }}
        </h2>
    </x-slot>

    @php
        $total = 0;
        $isAdmin = auth()->user()->hasRole('Admin');
    @endphp

    <div class="container mx-auto px-4 py-6">

        <!-- TOP BUTTONS -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-6">
            <div class="min-w-0">
                <h1 class="text-xl md:text-2xl font-bold text-gray-800 break-words">
                    {{ __('messages.proforma_invoices_management') }} #{{ $proformaInvoice->id }}
                </h1>
                <p class="text-gray-600 text-sm break-words">
                    {{ __('messages.client') }}: {{ $proformaInvoice->client->name }}
                </p>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 shrink-0">
                <!-- DOWNLOAD -->
                <a href="{{ route('proforma-invoices.download', $proformaInvoice->id) }}"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm text-center whitespace-nowrap">
                    {{ __('messages.download_excel') }}
                </a>

                <!-- EDIT -->
                @can('edit proforma invoices')
                    <a href="{{ route('proforma-invoices.edit', $proformaInvoice->id) }}"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-sm text-center whitespace-nowrap">
                        {{ __('messages.edit') }}
                    </a>
                @endcan

                <!-- CONVERT / VIEW CONVERTED -->
                @if ($proformaInvoice->commercialInvoice)
                    <a href="{{ route('commercial-invoices.show', $proformaInvoice->commercialInvoice) }}"
                        class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded text-sm text-center whitespace-nowrap">
                        {{ __('messages.view_commercial_invoice') }}
                        #{{ $proformaInvoice->commercialInvoice->invoice_no ?? $proformaInvoice->commercialInvoice->id }}
                    </a>
                @else
                    @can('create commercial invoices')
                        <a href="{{ route('commercial-invoices.create', ['from_proforma' => $proformaInvoice->id]) }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm text-center whitespace-nowrap">
                            {{ __('messages.convert_to_commercial') }}
                        </a>
                    @endcan
                @endif

                <!-- BACK -->
                <a href="{{ route('proforma-invoices.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm text-center whitespace-nowrap">
                    {{ __('messages.back') }}
                </a>
            </div>
        </div>

        <!-- MAIN CARD -->
        <div class="bg-white shadow-lg rounded-lg p-4 md:p-8">
            <!-- TITLE -->
            <div class="text-center mb-6 md:mb-8">
                <h1 class="text-2xl md:text-3xl font-bold">
                    {{ strtoupper(__('messages.proforma_invoices_management')) }}
                </h1>
            </div>

            <!-- CLIENT + SHIPPING -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 mb-8">
                <!-- CLIENT -->
                <div class="min-w-0 text-sm md:text-base">
                    <h2 class="font-bold text-base md:text-lg mb-3">{{ __('messages.client_info') }}</h2>
                    <p class="mb-2 break-words"><strong>TO:</strong> {{ $proformaInvoice->client->name }}</p>
                    <p class="mb-2 break-words"><strong>ADD:</strong> {{ $proformaInvoice->client->address }}</p>
                    <p class="mb-2 break-words"><strong>TEL:</strong> {{ $proformaInvoice->client->phone }}</p>
                </div>

                <!-- SHIPPING -->
                <div class="min-w-0 text-sm md:text-base">
                    <h2 class="font-bold text-base md:text-lg mb-3">{{ __('messages.shipping_info') }}</h2>
                    <p class="mb-2 break-words"><strong>{{ __('messages.port_of_loading') }}:</strong>
                        {{ $proformaInvoice->port_of_loading }}</p>
                    <p class="mb-2 break-words"><strong>{{ __('messages.port_of_discharge') }}:</strong>
                        {{ $proformaInvoice->port_of_discharge }}</p>
                    <p class="mb-2"><strong>{{ __('messages.date') }}:</strong> {{ $proformaInvoice->date }}</p>
                    <p class="mb-2 break-words"><strong>{{ __('messages.container_no') }}:</strong>
                        {{ $proformaInvoice->container_no }}</p>
                    <p class="mb-2 break-words"><strong>{{ __('messages.seal_no') }}:</strong>
                        {{ $proformaInvoice->seal_no }}</p>
                </div>
            </div>

            <!-- ============ MOBILE / TABLET : PRODUCT CARDS ============ -->
            <div class="lg:hidden space-y-3">
                @foreach ($proformaInvoice->products as $product)
                    @php
                        $qty = $product->pivot->ctn * $product->pcs_cts;
                        $amount = $qty * $product->pivot->unit_price;
                        $total += $amount;
                    @endphp
                    <div class="border rounded-lg p-3 text-sm">
                        <div class="flex gap-3">
                            <div class="shrink-0">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                        alt="{{ $product->reference }}" class="w-16 h-16 object-cover rounded border">
                                @else
                                    <div
                                        class="w-16 h-16 bg-gray-100 flex items-center justify-center text-gray-400 text-xs rounded border">
                                        NO IMG</div>
                                @endif
                            </div>
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
                            <p><span class="text-gray-500">{{ __('messages.qty') }}:</span> {{ $qty }}</p>
                            <p><span class="text-gray-500">{{ __('messages.unit_price') }}:</span>
                                {{ number_format($product->pivot->unit_price, 2) }} {{ $proformaInvoice->currency }}
                            </p>
                            <p class="col-span-2 font-semibold"><span
                                    class="text-gray-500 font-normal">{{ __('messages.total') }}:</span>
                                {{ number_format($amount, 2) }} {{ $proformaInvoice->currency }}</p>
                        </div>
                    </div>
                @endforeach

                <!-- TOTALS CARD -->
                <div class="bg-gray-100 rounded-lg p-3 text-sm">
                    <div class="flex justify-between py-1">
                        <span class="text-gray-600">{{ __('messages.total_exw') }}</span>
                        <span class="font-semibold">{{ number_format($total, 2) }}
                            {{ $proformaInvoice->currency }}</span>
                    </div>
                    @if ($isAdmin)
                        <div class="flex justify-between py-1">
                            <span class="text-gray-600">{{ __('messages.commission_3') }}</span>
                            <span class="font-semibold">{{ number_format($total * 0.03, 2) }}
                                {{ $proformaInvoice->currency }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between py-1">
                        <span class="text-gray-600">{{ __('messages.local_charge') }}</span>
                        <span class="font-semibold">{{ number_format($proformaInvoice->local_charge, 2) }}
                            {{ $proformaInvoice->currency }}</span>
                    </div>
                    <div
                        class="flex justify-between py-2 mt-2 border-t border-gray-300 bg-yellow-100 -mx-3 px-3 rounded-b-lg">
                        <span class="font-bold">{{ __('messages.fob_total') }}</span>
                        <span class="font-bold">
                            @php
                                $mobileFobTotal =
                                    $total + $proformaInvoice->local_charge + ($isAdmin ? $total * 0.03 : 0);
                            @endphp
                            {{ number_format($mobileFobTotal, 2) }} {{ $proformaInvoice->currency }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- ============ DESKTOP : TABLE ============ -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full border border-gray-400">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border p-2">{{ strtoupper(__('messages.image')) }}</th>
                            <th class="border p-2">ITEM NO</th>
                            <th class="border p-2">{{ strtoupper(__('messages.description')) }}</th>
                            <th class="border p-2">{{ __('messages.ctn') }}</th>
                            <th class="border p-2">{{ __('messages.pcs_cts') }}</th>
                            <th class="border p-2">{{ __('messages.qty') }}</th>
                            <th class="border p-2">{{ __('messages.unit_price') }}</th>
                            <th class="border p-2">{{ __('messages.total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach ($proformaInvoice->products as $product)
                            @php
                                $qty = $product->pivot->ctn * $product->pcs_cts;
                                $amount = $qty * $product->pivot->unit_price;
                                $total += $amount;
                            @endphp
                            <tr>
                                <td class="border p-2 text-center">
                                    @if ($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                            alt="{{ $product->reference }}"
                                            class="w-24 h-24 object-cover rounded mx-auto border">
                                    @else
                                        <div
                                            class="w-24 h-24 bg-gray-100 flex items-center justify-center text-gray-400 text-xs mx-auto rounded border">
                                            NO IMAGE</div>
                                    @endif
                                </td>
                                <td class="border p-2 text-center">{{ $product->reference }}</td>
                                <td class="border p-2">{{ $product->description }}</td>
                                <td class="border p-2 text-center">{{ $product->pivot->ctn }}</td>
                                <td class="border p-2 text-center">{{ $product->pcs_cts }}</td>
                                <td class="border p-2 text-center">{{ $qty }}</td>
                                <td class="border p-2 text-center">{{ number_format($product->pivot->unit_price, 2) }}
                                    {{ $proformaInvoice->currency }}</td>
                                <td class="border p-2 text-center font-bold">{{ number_format($amount, 2) }}
                                    {{ $proformaInvoice->currency }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-100 font-bold">
                        <tr>
                            <td colspan="7" class="border p-2 text-right">{{ __('messages.total_exw') }}</td>
                            <td class="border p-2 text-center">{{ number_format($total, 2) }}
                                {{ $proformaInvoice->currency }}</td>
                        </tr>
                        @if ($isAdmin)
                            <tr>
                                <td colspan="7" class="border p-2 text-right">{{ __('messages.commission_3') }}</td>
                                <td class="border p-2 text-center">{{ number_format($total * 0.03, 2) }}
                                    {{ $proformaInvoice->currency }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="7" class="border p-2 text-right">{{ __('messages.local_charge') }}</td>
                            <td class="border p-2 text-center">{{ number_format($proformaInvoice->local_charge, 2) }}
                                {{ $proformaInvoice->currency }}</td>
                        </tr>
                        <tr class="bg-yellow-100">
                            <td colspan="7" class="border p-3 text-right text-lg">{{ __('messages.fob_total') }}
                            </td>
                            <td class="border p-3 text-center text-lg">
                                @php
                                    $desktopFobTotal =
                                        $total + $proformaInvoice->local_charge + ($isAdmin ? $total * 0.03 : 0);
                                @endphp
                                {{ number_format($desktopFobTotal, 2) }} {{ $proformaInvoice->currency }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
