<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
            Create Packing List
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <h1 class="text-xl md:text-2xl font-bold mb-6">
            Create Packing List
        </h1>

        <form action="{{ route('packing-lists.store') }}" method="POST">
            @csrf

            <div class="bg-white shadow rounded-lg p-4 md:p-6">

                <!-- CLIENT -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Client
                    </label>
                    <select name="client_id" id="client_id" class="border rounded w-full py-2 px-3">
                        <option value="">Select Client</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}">
                                {{ $client->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- SHIPPING INFO -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="min-w-0">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Port Of Loading</label>
                        <input type="text" name="port_of_loading" class="border rounded w-full py-2 px-3">
                    </div>
                    <div class="min-w-0">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Port Of Discharge</label>
                        <input type="text" name="port_of_discharge" class="border rounded w-full py-2 px-3">
                    </div>
                    <div class="min-w-0">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Date</label>
                        <input type="date" name="date" class="border rounded w-full py-2 px-3">
                    </div>
                    <div class="min-w-0">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Container No</label>
                        <input type="text" name="container_no" class="border rounded w-full py-2 px-3">
                    </div>
                    <div class="min-w-0">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Seal No</label>
                        <input type="text" name="seal_no" class="border rounded w-full py-2 px-3">
                    </div>
                </div>

                <!-- PRODUCTS -->
                <div class="mt-10">
                    <h2 class="text-lg md:text-xl font-bold mb-4">Products</h2>

                    <table class="products-table w-full border border-gray-300">
                        <thead class="hidden lg:table-header-group bg-gray-100">
                            <tr>
                                <th class="border p-2">Product</th>
                                <th class="border p-2">CTN</th>
                                <th class="border p-2">PCS/CTS</th>
                                <th class="border p-2">UNIT CBM</th>
                                <th class="border p-2">UNIT GW</th>
                                <th class="border p-2">UNIT NW</th>
                                <th class="border p-2">TOTAL CBM</th>
                                <th class="border p-2">TOTAL GW</th>
                                <th class="border p-2">TOTAL NW</th>
                                <th class="border p-2">Action</th>
                            </tr>
                        </thead>
                        <tbody id="products-table-body" class="block lg:table-row-group">
                            <!-- Rows will be injected via JS to handle the caching and dropdown logic smoothly -->
                        </tbody>
                    </table>

                    <button type="button" id="add-product-row"
                        class="mt-4 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full sm:w-auto">
                        Add Product
                    </button>
                </div>

                <button type="submit"
                    class="mt-6 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full sm:w-auto">
                    Save Packing List
                </button>
            </div>
        </form>
    </div>

    <style>
        @media (max-width: 1023px) {
            .products-table td[data-label]::before {
                content: attr(data-label);
                display: block;
                font-weight: 600;
                color: #6b7280;
                font-size: 0.75rem;
                text-transform: uppercase;
                margin-bottom: 0.25rem;
            }
        }
    </style>

    <script>
        let rowIndex = 0;
        let allProductOptions = [];

        // 1. Cache all products so we can rebuild dropdowns dynamically
        function cacheProductOptions() {
            allProductOptions = [];
            // Get the product data passed from the controller directly
            @foreach ($products as $product)
                allProductOptions.push({
                    value: "{{ $product->id }}",
                    text: "{{ $product->reference }}",
                    pcs: "{{ $product->pcs_cts }}",
                    cbm: "{{ $product->unit_cbm }}",
                    gw: "{{ $product->unit_gw }}",
                    nw: "{{ $product->unit_nw }}"
                });
            @endforeach
        }

        // 2. Rebuild dropdowns to prevent duplicates
        function updateProductDropdowns() {
            let selectedIds = [];

            // Get all selected IDs
            document.querySelectorAll('.product-select').forEach(function(select) {
                if (select.value) selectedIds.push(select.value);
            });

            // Rebuild each dropdown
            document.querySelectorAll('.product-select').forEach(function(currentSelect) {
                let currentValue = currentSelect.value;

                // Clear all options except placeholder
                currentSelect.innerHTML = '<option value="">Select Product</option>';

                // Re-add available options
                allProductOptions.forEach(function(opt) {
                    // If it's not selected anywhere else, OR it's the currently selected one for this row
                    if (!selectedIds.includes(opt.value) || opt.value === currentValue) {
                        let newOption = document.createElement('option');
                        newOption.value = opt.value;
                        newOption.text = opt.text;
                        newOption.setAttribute('data-pcs', opt.pcs);
                        newOption.setAttribute('data-cbm', opt.cbm);
                        newOption.setAttribute('data-gw', opt.gw);
                        newOption.setAttribute('data-nw', opt.nw);

                        if (opt.value === currentValue) {
                            newOption.selected = true;
                        }
                        currentSelect.appendChild(newOption);
                    }
                });
            });
        }

        // 3. Add a new row
        function addProductRow() {
            let tbody = document.getElementById('products-table-body');

            let row = `
                <tr class="block lg:table-row border-b lg:border-0 mb-4 lg:mb-0 p-3 lg:p-0 rounded-lg lg:rounded-none">
                    <td class="block lg:table-cell border-0 lg:border p-2" data-label="Product">
                        <select name="products[${rowIndex}][id]" class="w-full border rounded p-2 product-select" onchange="handleProductChange(this)">
                            <option value="">Select Product</option>
                        </select>
                    </td>

                    <td class="block lg:table-cell border-0 lg:border p-2" data-label="CTN">
                        <input type="number" name="products[${rowIndex}][ctn]" class="w-full border rounded p-2 ctn-input" oninput="calculateRowTotals(this.closest('tr'))">
                    </td>

                    <td class="block lg:table-cell border-0 lg:border p-2 pcs-cell" data-label="PCS/CTS">-</td>
                    <td class="block lg:table-cell border-0 lg:border p-2 cbm-cell" data-label="UNIT CBM">-</td>
                    <td class="block lg:table-cell border-0 lg:border p-2 gw-cell" data-label="UNIT GW">-</td>
                    <td class="block lg:table-cell border-0 lg:border p-2 nw-cell" data-label="UNIT NW">-</td>
                    <td class="block lg:table-cell border-0 lg:border p-2 total-cbm-cell" data-label="TOTAL CBM">-</td>
                    <td class="block lg:table-cell border-0 lg:border p-2 total-gw-cell" data-label="TOTAL GW">-</td>
                    <td class="block lg:table-cell border-0 lg:border p-2 total-nw-cell" data-label="TOTAL NW">-</td>

                    <td class="block lg:table-cell border-0 lg:border p-2 text-center" data-label="">
                        <button type="button" onclick="this.closest('tr').remove(); updateProductDropdowns();" class="text-red-600 hover:text-red-800">✕</button>
                    </td>
                </tr>
            `;

            tbody.insertAdjacentHTML('beforeend', row);
            rowIndex++;

            // Immediately update dropdowns so the new row gets populated
            updateProductDropdowns();
        }

        // 4. Handle product selection change
        function handleProductChange(select) {
            let row = select.closest('tr');
            let option = select.options[select.selectedIndex];

            // Update the read-only cells
            row.querySelector('.pcs-cell').innerText = option.dataset.pcs || '-';
            row.querySelector('.cbm-cell').innerText = option.dataset.cbm || '-';
            row.querySelector('.gw-cell').innerText = option.dataset.gw || '-';
            row.querySelector('.nw-cell').innerText = option.dataset.nw || '-';

            calculateRowTotals(row);
            updateProductDropdowns();
        }

        // 5. Calculate totals
        function calculateRowTotals(row) {
            let ctn = parseFloat(row.querySelector('.ctn-input').value) || 0;
            let cbm = parseFloat(row.querySelector('.cbm-cell').innerText) || 0;
            let gw = parseFloat(row.querySelector('.gw-cell').innerText) || 0;
            let nw = parseFloat(row.querySelector('.nw-cell').innerText) || 0;

            row.querySelector('.total-cbm-cell').innerText = (ctn * cbm).toFixed(3);
            row.querySelector('.total-gw-cell').innerText = (ctn * gw).toFixed(2);
            row.querySelector('.total-nw-cell').innerText = (ctn * nw).toFixed(2);
        }

        // 6. Initialize
        document.addEventListener('DOMContentLoaded', function() {
            cacheProductOptions();
            addProductRow(); // Add the first row
        });

        document.getElementById('add-product-row').addEventListener('click', addProductRow);
    </script>

</x-app-layout>
