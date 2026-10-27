<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
            {{ __('messages.commercial_invoice_details') }}
        </h2>
    </x-slot>

    @php
        $total = 0;
    @endphp

    <div class="container mx-auto px-4 py-6">

        <!-- TOP -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-6">
            <div class="min-w-0">
                <h1 class="text-xl md:text-2xl font-bold text-gray-800 break-words">
                    {{ __('messages.commercial_invoices_management') }} #{{ $commercialInvoice->invoice_no }}
                </h1>
                <p class="text-gray-600 text-sm break-words">
                    {{ __('messages.client') }}: {{ $commercialInvoice->client->name }}
                </p>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 shrink-0">
                <!-- EXCEL -->
                <a href="{{ route('commercial-invoices.download', $commercialInvoice) }}"
                    class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded text-sm text-center whitespace-nowrap">
                    {{ __('messages.download_excel') }}
                </a>
                <!-- EDIT -->
                @can('edit commercial invoices')
                    <a href="{{ route('commercial-invoices.edit', $commercialInvoice) }}"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm text-center whitespace-nowrap">
                        {{ __('messages.edit') }}
                    </a>
                @endcan
                <!-- BACK -->
                <a href="{{ route('commercial-invoices.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm text-center whitespace-nowrap">
                    {{ __('messages.back') }}
                </a>
            </div>
        </div>

        <!-- MAIN CARD -->
        <div class="bg-white shadow-lg rounded-lg p-4 md:p-8">
            <div class="text-center mb-6 md:mb-8">
                <h1 class="text-2xl md:text-3xl font-bold">
                    {{ strtoupper(__('messages.commercial_invoices_management')) }}</h1>
            </div>

            <!-- INFO -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 mb-8 text-sm md:text-base">
                <div class="min-w-0">
                    <p class="mb-2 break-words"><strong>TO:</strong> {{ $commercialInvoice->client->name }}</p>
                    <p class="mb-2 break-words"><strong>ADD:</strong> {{ $commercialInvoice->client->address }}</p>
                    <p class="mb-2 break-words"><strong>TEL:</strong> {{ $commercialInvoice->client->phone }}</p>
                </div>
                <div class="min-w-0">
                    <p class="mb-2"><strong>{{ __('messages.date') }}:</strong> {{ $commercialInvoice->date }}</p>
                    <p class="mb-2 break-words"><strong>{{ __('messages.invoice_no') }}:</strong>
                        {{ $commercialInvoice->invoice_no }}</p>
                    <p class="mb-2 break-words"><strong>{{ __('messages.mode_of_delivery') }}:</strong>
                        {{ $commercialInvoice->mode_of_delivery }}</p>
                    <p class="mb-2 break-words"><strong>{{ __('messages.country_of_origin') }}:</strong>
                        {{ $commercialInvoice->country_of_origin }}</p>
                </div>
            </div>

            <!-- PORTS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-8 mb-8 text-sm md:text-base">
                <div class="min-w-0 break-words"><strong>{{ __('messages.port_of_loading') }}:</strong>
                    {{ $commercialInvoice->port_of_loading }}</div>
                <div class="min-w-0 break-words"><strong>{{ __('messages.port_of_discharge') }}:</strong>
                    {{ $commercialInvoice->port_of_discharge }}</div>
            </div>

            <!-- MOBILE / TABLET : PRODUCT CARDS -->
            <div class="lg:hidden space-y-3">
                @foreach ($commercialInvoice->products as $product)
                    @php
                        $qty = $product->pivot->ctn * $product->pcs_cts;
                        $amount = $qty * $product->pivot->unit_price;
                        $total += $amount;
                    @endphp
                    <div class="border rounded-lg p-3 text-sm">
                        <p class="font-semibold text-gray-900 break-words">{{ $product->reference }}</p>
                        <p class="text-gray-600 break-words mb-2">{{ strtoupper($product->description) }}</p>
                        <div class="grid grid-cols-2 gap-2 text-gray-700">
                            <p><span class="text-gray-500">{{ __('messages.ctn') }}:</span> {{ $product->pivot->ctn }}
                            </p>
                            <p><span class="text-gray-500">{{ __('messages.pcs_cts') }}:</span>
                                {{ $product->pcs_cts }}</p>
                            <p><span class="text-gray-500">{{ __('messages.qty') }}:</span> {{ $qty }}</p>
                            <p><span class="text-gray-500">{{ __('messages.unit_price') }}:</span>
                                {{ number_format($product->pivot->unit_price, 2) }} {{ $commercialInvoice->currency }}
                            </p>
                            <p class="col-span-2 font-semibold"><span
                                    class="text-gray-500 font-normal">{{ __('messages.total') }}:</span>
                                {{ number_format($amount, 2) }} {{ $commercialInvoice->currency }}</p>
                        </div>
                    </div>
                @endforeach

                <div class="bg-gray-100 rounded-lg p-3 flex justify-between items-center text-sm font-bold">
                    <span>{{ __('messages.total_amount_fob') }} {{ $commercialInvoice->currency }}</span>
                    <span>{{ number_format($total, 2) }} {{ $commercialInvoice->currency }}</span>
                </div>
            </div>

            <!-- DESKTOP : TABLE -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full border border-gray-400">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border p-2">ITEM NO</th>
                            <th class="border p-2">{{ strtoupper(__('messages.description')) }}</th>
                            <th class="border p-2">{{ __('messages.ctn') }}</th>
                            <th class="border p-2">{{ __('messages.pcs_cts') }}</th>
                            <th class="border p-2">{{ __('messages.qty') }}</th>
                            <th class="border p-2">{{ __('messages.unit_price') }} {{ $commercialInvoice->currency }}
                            </th>
                            <th class="border p-2">{{ __('messages.total') }} {{ $commercialInvoice->currency }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach ($commercialInvoice->products as $product)
                            @php
                                $qty = $product->pivot->ctn * $product->pcs_cts;
                                $amount = $qty * $product->pivot->unit_price;
                                $total += $amount;
                            @endphp
                            <tr>
                                <td class="border p-2 text-center">{{ $product->reference }}</td>
                                <td class="border p-2 text-center">{{ strtoupper($product->description) }}</td>
                                <td class="border p-2 text-center">{{ $product->pivot->ctn }}</td>
                                <td class="border p-2 text-center">{{ $product->pcs_cts }}</td>
                                <td class="border p-2 text-center">{{ $qty }}</td>
                                <td class="border p-2 text-center">{{ number_format($product->pivot->unit_price, 2) }}
                                    {{ $commercialInvoice->currency }}</td>
                                <td class="border p-2 text-center font-bold">{{ number_format($amount, 2) }}
                                    {{ $commercialInvoice->currency }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-100 font-bold">
                            <td colspan="6" class="border p-3 text-center">{{ __('messages.total_amount_fob') }}
                                {{ $commercialInvoice->currency }}</td>
                            <td class="border p-3 text-center">{{ number_format($total, 2) }}
                                {{ $commercialInvoice->currency }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- BANK INFO -->
            <div class="mt-8 space-y-2 font-semibold text-sm md:text-base break-words">
                <p>{{ __('messages.term_of_payment') }} {{ __('messages.transfer_after_receive') }}</p>
                <p>{{ __('messages.beneficiary_name') }} {{ $commercialInvoice->bankAccount?->beneficiary_name }}</p>
                <p>{{ __('messages.account_no') }} {{ $commercialInvoice->bankAccount?->account_number }}</p>
                <p>SWIFT : {{ $commercialInvoice->bankAccount?->swift }}</p>
                <p>{{ __('messages.beneficiary_bank') }} {{ $commercialInvoice->bankAccount?->bank_name }}</p>
                <p>{{ __('messages.bank_address') }} {{ $commercialInvoice->bankAccount?->bank_address }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
