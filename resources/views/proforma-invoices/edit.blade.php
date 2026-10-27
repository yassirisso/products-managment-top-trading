<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
            {{ __('messages.edit_proforma_invoice') }} #{{ $proformaInvoice->id }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <div class="bg-white shadow-md rounded-lg p-4 md:p-6">

            <form action="{{ route('proforma-invoices.update', $proformaInvoice) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6 mb-6">
                    <!-- CLIENT -->
                    <div class="min-w-0">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.client') }}</label>
                        <select name="client_id" required class="w-full border rounded-lg px-3 py-2">
                            <option value="">{{ __('messages.client') }}</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}"
                                    {{ old('client_id', $proformaInvoice->client_id) == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- CURRENCY -->
                    <div class="min-w-0">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.currency') }}</label>
                        <select name="currency" class="w-full border rounded-lg px-3 py-2" required>
                            <option value="RMB"
                                {{ old('currency', $proformaInvoice->currency) == 'RMB' ? 'selected' : '' }}>RMB (¥)
                            </option>
                            <option value="USD"
                                {{ old('currency', $proformaInvoice->currency) == 'USD' ? 'selected' : '' }}>USD ($)
                            </option>
                        </select>
                    </div>

                    <!-- DATE -->
                    <div class="min-w-0">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.date') }}</label>
                        <input type="date" name="date" value="{{ old('date', $proformaInvoice->date) }}"
                            class="w-full border rounded-lg px-3 py-2">
                    </div>

                    <!-- CONTAINER -->
                    <div class="min-w-0">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.container_no') }}</label>
                        <input type="text" name="container_no"
                            value="{{ old('container_no', $proformaInvoice->container_no) }}"
                            class="w-full border rounded-lg px-3 py-2">
                    </div>

                    <!-- SEAL -->
                    <div class="min-w-0">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.seal_no') }}</label>
                        <input type="text" name="seal_no" value="{{ old('seal_no', $proformaInvoice->seal_no) }}"
                            class="w-full border rounded-lg px-3 py-2">
                    </div>

                    <!-- PORT LOADING -->
                    <div class="min-w-0">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.port_of_loading') }}</label>
                        <input type="text" name="port_of_loading"
                            value="{{ old('port_of_loading', $proformaInvoice->port_of_loading) }}"
                            class="w-full border rounded-lg px-3 py-2">
                    </div>

                    <!-- PORT DISCHARGE -->
                    <div class="min-w-0">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.port_of_discharge') }}</label>
                        <input type="text" name="port_of_discharge"
                            value="{{ old('port_of_discharge', $proformaInvoice->port_of_discharge) }}"
                            class="w-full border rounded-lg px-3 py-2">
                    </div>

                    <!-- LOCAL CHARGE -->
                    <div class="min-w-0">
                        @php
                            $totalWarehouseFees = $proformaInvoice->warehouseFees->sum('montant_rmb');
                            $displayLocalCharge =
                                $totalWarehouseFees > 0 ? $totalWarehouseFees : $proformaInvoice->local_charge ?? 0;
                        @endphp

                        <label
                            class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.local_charge') }}</label>
                        <input type="number" step="0.01" name="local_charge"
                            value="{{ old('local_charge', $displayLocalCharge) }}" readonly
                            class="w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-600 cursor-not-allowed">

                        @if ($totalWarehouseFees > 0)
                            <div class="mt-2 bg-blue-50 border border-blue-200 text-blue-800 text-xs rounded p-2">
                                <p class="font-semibold mb-1">{{ __('messages.warehouse_fees_for_invoice') }}</p>
                                <ul class="list-disc list-inside mb-2">
                                    @foreach ($proformaInvoice->warehouseFees as $fee)
                                        <li>
                                            {{ $fee->date->format('Y-m-d') }} ({{ $fee->container_no }}):
                                            <strong>{{ number_format($fee->montant_rmb, 2) }} ¥</strong>
                                        </li>
                                    @endforeach
                                </ul>
                                <p class="font-bold border-t border-blue-200 pt-1">
                                    {{ __('messages.total_warehouse_fees') }}
                                    {{ number_format($totalWarehouseFees, 2) }} ¥
                                </p>
                            </div>
                        @endif
                    </div>

                </div>

                <!-- PRODUCTS -->
                <div class="mb-6">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-4">
                        <h3 class="text-lg font-bold">{{ __('messages.products') }}</h3>
                        <button type="button" onclick="addProductRow()"
                            class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded text-sm text-center w-full sm:w-auto">
                            {{ __('messages.add_product') }}
                        </button>
                    </div>
                    <div id="products-container" class="space-y-4"></div>
                </div>

                <!-- SUBMIT -->
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
                    <a href="{{ route('proforma-invoices.show', $proformaInvoice) }}"
                        class="text-gray-600 hover:text-gray-800 text-center sm:text-left py-2">
                        {{ __('messages.cancel') }}
                    </a>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-2 rounded w-full sm:w-auto">
                        {{ __('messages.update_proforma_invoice') }}
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        let productIndex = 0;

        function addProductRow(prefill = null) {
            const container = document.getElementById('products-container');
            const row = document.createElement('div');

            row.className = 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 border p-4 rounded-lg bg-gray-50';

            row.innerHTML = `
                <div class="min-w-0 sm:col-span-2 lg:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.product') }}</label>
                    <select name="products[${productIndex}][id]" class="w-full border rounded-lg px-3 py-2 product-select" required onchange="updatePrice(this)">
                        <option value="">{{ __('messages.product') }}</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price ?? 0 }}">
                                {{ $product->reference }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="min-w-0">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.ctn') }}</label>
                    <input type="number" name="products[${productIndex}][ctn]" value="0" min="0" class="w-full border rounded-lg px-3 py-2 ctn-input">
                </div>

                <div class="min-w-0">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.unit_price') }}</label>
                    <input type="number" step="0.01" name="products[${productIndex}][unit_price]" value="0" min="0" class="w-full border rounded-lg px-3 py-2 unit-price">
                </div>

                <div class="flex items-end">
                    <button type="button" onclick="this.closest('.grid').remove()" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded w-full lg:w-auto">
                        {{ __('messages.remove') }}
                    </button>
                </div>
            `;

            container.appendChild(row);

            if (prefill) {
                const select = row.querySelector('.product-select');
                select.value = prefill.id;
                row.querySelector('.ctn-input').value = prefill.ctn;
                row.querySelector('.unit-price').value = prefill.unit_price;
            }

            productIndex++;
        }

        function updatePrice(selectElement) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            const row = selectElement.closest('.grid');
            row.querySelector('.unit-price').value = price;
        }

        document.addEventListener('DOMContentLoaded', function() {
            @if ($proformaInvoice->products->count())
                @foreach ($proformaInvoice->products as $product)
                    addProductRow({
                        id: {{ $product->id }},
                        ctn: {{ $product->pivot->ctn }},
                        unit_price: {{ $product->pivot->unit_price }},
                    });
                @endforeach
            @else
                addProductRow();
            @endif
        });
    </script>

</x-app-layout>
