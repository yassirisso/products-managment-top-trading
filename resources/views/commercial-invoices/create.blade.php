<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

            Create Commercial Invoice

        </h2>

    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <div class="bg-white shadow-md rounded-lg p-6">

            <form action="{{ route('commercial-invoices.store') }}"
                  method="POST">

                @csrf

                <!-- TOP FORM -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                    <!-- CLIENT -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">

                            Client

                        </label>

                        <select name="client_id"
                                required
                                class="w-full border rounded-lg px-3 py-2">

                            <option value="">

                                Select Client

                            </option>

                            @foreach($clients as $client)

                                <option value="{{ $client->id }}">

                                    {{ $client->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <!-- DATE -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">

                            Date

                        </label>

                        <input type="date"
                               name="date"
                               class="w-full border rounded-lg px-3 py-2">

                    </div>

                    <!-- INVOICE -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">

                            Invoice No

                        </label>

                        <input type="text"
                               name="invoice_no"
                               class="w-full border rounded-lg px-3 py-2">

                    </div>

                    <!-- MODE -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">

                            Mode Of Delivery

                        </label>

                        <input type="text"
                               name="mode_of_delivery"
                               value="FOB"
                               class="w-full border rounded-lg px-3 py-2">

                    </div>

                    <!-- PORT LOADING -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">

                            Port Of Loading

                        </label>

                        <input type="text"
                               name="port_of_loading"
                               class="w-full border rounded-lg px-3 py-2">

                    </div>

                    <!-- PORT DISCHARGE -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">

                            Port Of Discharge

                        </label>

                        <input type="text"
                               name="port_of_discharge"
                               class="w-full border rounded-lg px-3 py-2">

                    </div>

                    <!-- COUNTRY -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">

                            Country Of Origin

                        </label>

                        <input type="text"
                               name="country_of_origin"
                               value="China"
                               class="w-full border rounded-lg px-3 py-2">

                    </div>

                    <!-- BANK -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">

                            Bank Account

                        </label>

                        <select
                            name="bank_account_id"
                            class="w-full border rounded-lg px-3 py-2"
                        >

                            <option value="">

                                Select Bank Account

                            </option>

                            @foreach($bankAccounts as $bank)

                                <option value="{{ $bank->id }}">

                                    {{ $bank->bank_name }}
                                    -
                                    {{ $bank->account_number }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

                <!-- PRODUCTS -->
                <div class="mb-6">

                    <div class="flex justify-between items-center mb-4">

                        <h3 class="text-lg font-bold">

                            Products

                        </h3>

                        <button type="button"
                                onclick="addProductRow()"
                                class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">

                            Add Product

                        </button>

                    </div>

                    <div id="products-container">

                    </div>

                </div>

                <!-- SUBMIT -->
                <div class="flex justify-end">

                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-2 rounded">

                        Create Commercial Invoice

                    </button>

                </div>

            </form>

        </div>

    </div>

    <!-- PRODUCTS SCRIPT -->
    <script>

        let productIndex = 0;

        function addProductRow()
        {
            const container = document.getElementById('products-container');

            const row = document.createElement('div');

            row.className =
                'grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 border p-4 rounded-lg bg-gray-50';

            row.innerHTML = `

                <!-- PRODUCT -->
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">

                        Product

                    </label>

                    <select
                        name="products[${productIndex}][id]"
                        required
                        onchange="updatePrice(this)"
                        class="w-full border rounded-lg px-3 py-2 product-select"
                    >

                        <option value="">

                            Select Product

                        </option>

                        @foreach($products as $product)

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
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-1">

                        CTN

                    </label>

                    <input
                        type="number"
                        name="products[${productIndex}][ctn]"
                        value="0"
                        min="0"
                        class="w-full border rounded-lg px-3 py-2"
                    >

                </div>

                <!-- PRICE -->
                <div>

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
                        onclick="this.parentElement.parentElement.remove()"
                        class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded"
                    >

                        Remove

                    </button>

                </div>
            `;

            container.appendChild(row);

            productIndex++;
        }

    </script>

    <script>

        function updatePrice(selectElement)
        {
            const selectedOption =
                selectElement.options[selectElement.selectedIndex];

            const price =
                selectedOption.getAttribute('data-price');

            const row =
                selectElement.closest('.grid');

            row.querySelector('.unit-price').value = price;
        }

    </script>

</x-app-layout>