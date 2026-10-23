<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">

            Edit Commercial Invoice #{{ $commercialInvoice->invoice_no ?? $commercialInvoice->id }}

        </h2>

    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <div class="bg-white shadow-md rounded-lg p-4 md:p-6">

            {{-- CONVERTED-FROM BANNER --}}
            @if ($commercialInvoice->proformaInvoice)
                <div class="bg-purple-50 border-l-4 border-purple-500 text-purple-800 p-4 mb-6 rounded text-sm">

                    <p class="font-semibold">
                        Converted from Proforma Invoice #{{ $commercialInvoice->proformaInvoice->id }}
                        ({{ $commercialInvoice->proformaInvoice->date }})
                    </p>

                    <a href="{{ route('proforma-invoices.show', $commercialInvoice->proformaInvoice) }}"
                       class="text-purple-700 underline">

                        View Proforma Invoice

                    </a>

                </div>
            @endif

            <form action="{{ route('commercial-invoices.update', $commercialInvoice) }}" method="POST">

                @csrf
                @method('PUT')

                <!-- TOP FORM -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6 mb-6">

                    <!-- CLIENT -->
                    <div class="min-w-0">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Client
                        </label>

                        <select name="client_id" required class="w-full border rounded-lg px-3 py-2">

                            <option value="">Select Client</option>

                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}"
                                    {{ old('client_id', $commercialInvoice->client_id) == $client->id ? 'selected' : '' }}>

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

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Currency
                        </label>

                        <select name="currency" class="w-full border rounded-lg px-3 py-2" required>

                            <option value="RMB"
                                {{ old('currency', $commercialInvoice->currency) == 'RMB' ? 'selected' : '' }}>
                                RMB (¥)
                            </option>

                            <option value="USD"
                                {{ old('currency', $commercialInvoice->currency) == 'USD' ? 'selected' : '' }}>
                                USD ($)
                            </option>

                        </select>

                    </div>

                    <!-- DATE -->
                    <div class="min-w-0">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Date
                        </label>

                        <input type="date" name="date"
                            value="{{ old('date', $commercialInvoice->date) }}"
                            class="w-full border rounded-lg px-3 py-2">

                    </div>

                    <!-- INVOICE NO -->
                    <div class="min-w-0">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Invoice No *
                        </label>

                        <input type="text" name="invoice_no"
                            value="{{ old('invoice_no', $commercialInvoice->invoice_no) }}"
                            required
                            class="w-full border rounded-lg px-3 py-2">

                        @error('invoice_no')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror

                    </div>

                    <!-- MODE -->
                    <div class="min-w-0">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Mode Of Delivery
                        </label>

                        <input type="text" name="mode_of_delivery"
                            value="{{ old('mode_of_delivery', $commercialInvoice->mode_of_delivery) }}"
                            class="w-full border rounded-lg px-3 py-2">

                    </div>

                    <!-- PORT LOADING -->
                    <div class="min-w-0">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Port Of Loading
                        </label>

                        <input type="text" name="port_of_loading"
                            value="{{ old('port_of_loading', $commercialInvoice->port_of_loading) }}"
                            class="w-full border rounded-lg px-3 py-2">

                    </div>

                    <!-- PORT DISCHARGE -->
                    <div class="min-w-0">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Port Of Discharge
                        </label>

                        <input type="text" name="port_of_discharge"
                            value="{{ old('port_of_discharge', $commercialInvoice->port_of_discharge) }}"
                            class="w-full border rounded-lg px-3 py-2">

                    </div>

                    <!-- COUNTRY -->
                    <div class="min-w-0">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Country Of Origin
                        </label>

                        <input type="text" name="country_of_origin"
                            value="{{ old('country_of_origin', $commercialInvoice->country_of_origin) }}"
                            class="w-full border rounded-lg px-3 py-2">

                    </div>

                    <!-- BANK -->
                    <div class="min-w-0 sm:col-span-2">

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Bank Account
                        </label>

                        <select name="bank_account_id" class="w-full border rounded-lg px-3 py-2">

                            <option value="">Select Bank Account</option>

                            @foreach ($bankAccounts as $bank)
                                <option value="{{ $bank->id }}"
                                    {{ old('bank_account_id', $commercialInvoice->bank_account_id) == $bank->id ? 'selected' : '' }}>

                                    {{ $bank->bank_name }} - {{ $bank->account_number }}

                                </option>
                            @endforeach

                        </select>

                    </div>

                </div>

                <!-- PRODUCTS -->
                <div class="mb-6">

                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-4">

                        <h3 class="text-lg font-bold">
                            Products
                        </h3>

                        <button type="button" onclick="addProductRow()"
                            class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded text-sm text-center w-full sm:w-auto">

                            Add Product

                        </button>

                    </div>

                    <div id="products-container" class="space-y-4"></div>

                </div>

                <!-- SUBMIT -->
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2">

                    <a href="{{ route('commercial-invoices.show', $commercialInvoice) }}"
                        class="text-gray-600 hover:text-gray-800 text-center sm:text-left py-2">

                        Cancel

                    </a>

                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-2 rounded w-full sm:w-auto">

                        Update Commercial Invoice

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

                <!-- PRODUCT -->
                <div class="min-w-0 sm:col-span-2 lg:col-span-1">

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Product
                    </label>

                    <select
                        name="products[${productIndex}][id]"
                        class="w-full border rounded-lg px-3 py-2 product-select"
                        required
                        onchange="updatePrice(this)"
                    >
                        <option value="">Select Product</option>

                        @foreach ($products as $product)
                            <option
                                value="{{ $product->id }}"
                                data-price="{{ $product->price ?? 0 }}"
                            >
                                {{ $product->reference }}
                            </option>
                        @endforeach

                    </select>

                </div>

                <!-- CTN -->
                <div class="min-w-0">

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        CTN
                    </label>

                    <input
                        type="number"
                        name="products[${productIndex}][ctn]"
                        value="0"
                        min="0"
                        class="w-full border rounded-lg px-3 py-2 ctn-input"
                    >

                </div>

                <!-- UNIT PRICE -->
                <div class="min-w-0">

                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Unit Price
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        name="products[${productIndex}][unit_price]"
                        value="0"
                        min="0"
                        class="w-full border rounded-lg px-3 py-2 unit-price"
                    >

                </div>

                <!-- REMOVE -->
                <div class="flex items-end">

                    <button
                        type="button"
                        onclick="this.closest('.grid').remove()"
                        class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded w-full lg:w-auto"
                    >
                        Remove
                    </button>

                </div>

            `;

            container.appendChild(row);

            // PRE-FILL (existing rows)
            if (prefill) {

                const select = row.querySelector('.product-select');
                select.value = prefill.id;

                row.querySelector('.ctn-input').value = prefill.ctn;
                row.querySelector('.unit-price').value = prefill.unit_price;
            }

            productIndex++;
        }
    </script>

    <script>
        function updatePrice(selectElement) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            const row = selectElement.closest('.grid');

            row.querySelector('.unit-price').value = price;
        }
    </script>

    {{-- PREFILL EXISTING PRODUCTS --}}
    @if ($commercialInvoice->products->count())
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                @foreach ($commercialInvoice->products as $product)
                    addProductRow({
                        id: {{ $product->id }},
                        ctn: {{ $product->pivot->ctn }},
                        unit_price: {{ $product->pivot->unit_price }},
                    });
                @endforeach

            });
        </script>
    @else
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                addProductRow();
            });
        </script>
    @endif

</x-app-layout>