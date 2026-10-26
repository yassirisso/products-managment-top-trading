<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
            Create Proforma Invoice
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <div class="bg-white shadow-md rounded-lg p-4 md:p-6">

            <form action="{{ route('proforma-invoices.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6 mb-6">
                    <!-- CLIENT -->
                    <div class="min-w-0">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                        <select name="client_id" required class="w-full border rounded-lg px-3 py-2">
                            <option value="">Select Client</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- CURRENCY -->
                    <div class="min-w-0">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                        <select name="currency" class="w-full border rounded-lg px-3 py-2" required>
                            <option value="RMB" selected>RMB (¥)</option>
                            <option value="USD">USD ($)</option>
                        </select>
                    </div>

                    <!-- DATE -->
                    <div class="min-w-0">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" name="date" class="w-full border rounded-lg px-3 py-2">
                    </div>

                    <!-- CONTAINER -->
                    <div class="min-w-0">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Container No</label>
                        <input type="text" name="container_no" class="w-full border rounded-lg px-3 py-2">
                    </div>

                    <!-- SEAL -->
                    <div class="min-w-0">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Seal No</label>
                        <input type="text" name="seal_no" class="w-full border rounded-lg px-3 py-2">
                    </div>

                    <!-- PORT LOADING -->
                    <div class="min-w-0">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Port Of Loading</label>
                        <input type="text" name="port_of_loading" class="w-full border rounded-lg px-3 py-2">
                    </div>

                    <!-- PORT DISCHARGE -->
                    <div class="min-w-0">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Port Of Discharge</label>
                        <input type="text" name="port_of_discharge" class="w-full border rounded-lg px-3 py-2">
                    </div>

                    <!-- LOCAL CHARGE -->
                    <div class="min-w-0">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Local Charge</label>
                        <input type="number" step="0.01" name="local_charge" value="0"
                            class="w-full border rounded-lg px-3 py-2">
                    </div>
                </div>

                <!-- PRODUCTS -->
                <div class="mb-6">
                    <h3 class="text-lg font-bold mb-4">Products</h3>
                    <div id="products-container" class="space-y-4"></div>

                    <button type="button" onclick="addProductRow()"
                        class="mt-2 bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded w-full sm:w-auto">
                        Add Product
                    </button>
                </div>

                <!-- SUBMIT -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-2 rounded w-full sm:w-auto">
                        Create Invoice
                    </button>
                </div>
            </form>

        </div>

    </div>

    <script>
        let productIndex = 0;
        let allProductOptions = [];

        // 1. Cache all products on load
        function cacheProductOptions() {
            allProductOptions = [];
            @foreach ($products as $product)
                allProductOptions.push({
                    value: "{{ $product->id }}",
                    text: "{{ $product->reference }}",
                    price: "{{ $product->price ?? 0 }}"
                });
            @endforeach
        }

        // 2. Rebuild dropdowns to prevent duplicates
        function updateProductDropdowns() {
            let selectedIds = [];

            document.querySelectorAll('.product-select').forEach(function(select) {
                if (select.value) selectedIds.push(select.value);
            });

            document.querySelectorAll('.product-select').forEach(function(currentSelect) {
                let currentValue = currentSelect.value;
                currentSelect.innerHTML = '<option value="">Select Product</option>';

                allProductOptions.forEach(function(opt) {
                    if (!selectedIds.includes(opt.value) || opt.value === currentValue) {
                        let newOption = document.createElement('option');
                        newOption.value = opt.value;
                        newOption.text = opt.text;
                        newOption.setAttribute('data-price', opt.price);
                        if (opt.value === currentValue) {
                            newOption.selected = true;
                        }
                        currentSelect.appendChild(newOption);
                    }
                });
            });
        }

        // 3. Add row
        function addProductRow() {
            const container = document.getElementById('products-container');
            const row = document.createElement('div');

            row.className = 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 border p-4 rounded-lg bg-gray-50';

            let selectName = 'products[' + productIndex + '][id]';
            let ctnName = 'products[' + productIndex + '][ctn]';
            let priceName = 'products[' + productIndex + '][unit_price]';

            row.innerHTML = `
                <div class="min-w-0 sm:col-span-2 lg:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product</label>
                    <select name="${selectName}" class="w-full border rounded-lg px-3 py-2 product-select" required onchange="updatePrice(this); updateProductDropdowns();">
                        <option value="">Select Product</option>
                    </select>
                </div>

                <div class="min-w-0">
                    <label class="block text-sm font-medium text-gray-700 mb-1">CTN</label>
                    <input type="number" name="${ctnName}" value="0" min="0" class="w-full border rounded-lg px-3 py-2">
                </div>

                <div class="min-w-0">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Price</label>
                    <input type="number" step="0.01" name="${priceName}" value="0" min="0" class="w-full border rounded-lg px-3 py-2 unit-price">
                </div>

                <div class="flex items-end">
                    <button type="button" onclick="this.closest('.grid').remove(); updateProductDropdowns();" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded w-full lg:w-auto">
                        Remove
                    </button>
                </div>
            `;

            container.appendChild(row);
            updateProductDropdowns(); // Populate new row
            productIndex++;
        }

        // 4. Update price
        function updatePrice(selectElement) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            const row = selectElement.closest('.grid');

            row.querySelector('.unit-price').value = price || 0;
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            cacheProductOptions();
            addProductRow();
        });
    </script>

</x-app-layout>
