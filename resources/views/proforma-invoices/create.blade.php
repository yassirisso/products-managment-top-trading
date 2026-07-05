<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

            Create Proforma Invoice

        </h2>

    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <div class="bg-white shadow-md rounded-lg p-6">

            <form action="{{ route('proforma-invoices.store') }}"
                  method="POST">

                @csrf

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

                    <!-- CONTAINER -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">

                            Container No

                        </label>

                        <input type="text"
                            name="container_no"
                            class="w-full border rounded-lg px-3 py-2">

                    </div>

                    <!-- SEAL -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">

                            Seal No

                        </label>

                        <input type="text"
                            name="seal_no"
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

                    <!-- LOCAL CHARGE -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">

                            Local Charge

                        </label>

                        <input type="number"
                            step="0.01"
                            name="local_charge"
                            value="0"
                            class="w-full border rounded-lg px-3 py-2">

                    </div>

                </div>

                <!-- PRODUCTS -->
                <div class="mb-6">

                    <h3 class="text-lg font-bold mb-4">

                        Products

                    </h3>

                    <div id="products-container">

                    </div>

                    <button type="button"
                            onclick="addProductRow()"
                            class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">

                        Add Product

                    </button>

                </div>

                <!-- SUBMIT -->
                <div class="flex justify-end">

                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-2 rounded">

                        Create Invoice

                    </button>

                </div>

            </form>

        </div>

    </div>

    <script>

    let productIndex = 0;

    function addProductRow()
    {
        const container = document.getElementById('products-container');

        const row = document.createElement('div');

        row.className = 'grid grid-cols-4 gap-4 mb-4 border p-4 rounded-lg bg-gray-50';

        row.innerHTML = `

            <!-- PRODUCT -->
            <div>

                <label class="block text-sm font-medium text-gray-700 mb-1">

                    Product

                </label>

                <select
                    name="products[${productIndex}][id]"
                    class="w-full border rounded-lg px-3 py-2 product-select"
                    required
                    onchange="updatePrice(this)"
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

            <!-- UNIT PRICE -->
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
                selectElement.options[
                    selectElement.selectedIndex
                ];

            const price =
                selectedOption.getAttribute('data-price');

            const row =
                selectElement.closest('.grid');

            row.querySelector('.unit-price').value = price;
        }

    </script>

</x-app-layout>