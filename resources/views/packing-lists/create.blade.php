<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
            {{ __('messages.create_packing_list') }}
        </h2>
    </x-slot>

    <style>
        /* Mobile responsive table styling */
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

    <div class="container mx-auto px-4 py-6">

        <h1 class="text-xl md:text-2xl font-bold mb-6">
            {{ __('messages.create_packing_list') }}
        </h1>

        <form action="{{ route('packing-lists.store') }}" method="POST">
            @csrf

            <div class="bg-white shadow rounded-lg p-4 md:p-6">

                <!-- ========================================== -->
                <!-- CLIENT & SHIPPING INFO                     -->
                <!-- ========================================== -->
                <div class="mb-6">
                    <div class="flex justify-between items-end mb-2">
                        <label class="block text-sm font-bold text-gray-700">
                            {{ __('messages.client') }}
                        </label>
                        <button type="button" onclick="openClientModal()"
                            class="text-sm text-blue-600 hover:text-blue-800 font-semibold">
                            + {{ __('messages.create_client') }}
                        </button>
                    </div>
                    <select name="client_id" id="client_id" class="border rounded w-full py-2 px-3">
                        <option value="">{{ __('messages.client') }}</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="min-w-0">
                        <label
                            class="block text-sm font-bold text-gray-700 mb-2">{{ __('messages.port_of_loading') }}</label>
                        <input type="text" name="port_of_loading" class="border rounded w-full py-2 px-3">
                    </div>
                    <div class="min-w-0">
                        <label
                            class="block text-sm font-bold text-gray-700 mb-2">{{ __('messages.port_of_discharge') }}</label>
                        <input type="text" name="port_of_discharge" class="border rounded w-full py-2 px-3">
                    </div>
                    <div class="min-w-0">
                        <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('messages.date') }}</label>
                        <input type="date" name="date" class="border rounded w-full py-2 px-3">
                    </div>
                    <div class="min-w-0">
                        <label
                            class="block text-sm font-bold text-gray-700 mb-2">{{ __('messages.container_no') }}</label>
                        <input type="text" name="container_no" class="border rounded w-full py-2 px-3">
                    </div>
                    <div class="min-w-0">
                        <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('messages.seal_no') }}</label>
                        <input type="text" name="seal_no" class="border rounded w-full py-2 px-3">
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- PRODUCTS TABLE                             -->
                <!-- ========================================== -->
                <div class="mt-10">
                    <h2 class="text-lg md:text-xl font-bold mb-4">{{ __('messages.products') }}</h2>

                    <table class="products-table w-full border border-gray-300">
                        <thead class="hidden lg:table-header-group bg-gray-100">
                            <tr>
                                <th class="border p-2">{{ __('messages.product') }}</th>
                                <th class="border p-2">{{ __('messages.ctn') }}</th>
                                <th class="border p-2">{{ __('messages.pcs_cts') }}</th>
                                <th class="border p-2">{{ __('messages.unit_cbm') }}</th>
                                <th class="border p-2">{{ __('messages.unit_gw') }}</th>
                                <th class="border p-2">{{ __('messages.unit_nw') }}</th>
                                <th class="border p-2">{{ __('messages.total_cbm') }}</th>
                                <th class="border p-2">{{ __('messages.total_gw') }}</th>
                                <th class="border p-2">{{ __('messages.total_nw') }}</th>
                                <th class="border p-2">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody id="products-table-body" class="block lg:table-row-group">
                            <!-- Rows injected via JS -->
                        </tbody>
                    </table>

                    <div class="mt-4 flex flex-col sm:flex-row gap-3">
                        <button type="button" id="add-product-row"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full sm:w-auto">
                            {{ __('messages.add_product') }}
                        </button>

                        <button type="button" onclick="openProductModal()"
                            class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded w-full sm:w-auto">
                            + {{ __('messages.create_new_product') }}
                        </button>
                    </div>
                </div>

                <!-- SUBMIT BUTTON -->
                <button type="submit"
                    class="mt-6 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full sm:w-auto">
                    {{ __('messages.save_packing_list') }}
                </button>
            </div>
        </form>
    </div>

    <!-- ========================================== -->
    <!-- MODALS (Separated for cleaner structure)   -->
    <!-- ========================================== -->

    <!-- CLIENT MODAL -->
    <div id="clientModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 overflow-hidden">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-bold">{{ __('messages.create_new_client') }}</h3>
                <button onclick="closeClientModal()" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>
            <form id="clientAjaxForm" class="p-4">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">{{ __('messages.client_name') }} *</label>
                    <input type="text" name="name" required class="w-full border rounded p-2">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">{{ __('messages.email') }}</label>
                    <input type="email" name="email" class="w-full border rounded p-2">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">{{ __('messages.phone') }}</label>
                    <input type="text" name="phone" class="w-full border rounded p-2">
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="closeClientModal()"
                        class="px-4 py-2 text-gray-600">{{ __('messages.cancel') }}</button>
                    <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded">{{ __('messages.save') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- PRODUCT MODAL -->
    <div id="productModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 overflow-hidden">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-bold">{{ __('messages.create_new_product') }}</h3>
                <button onclick="closeProductModal()" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>
            <form id="productAjaxForm" class="p-4">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">{{ __('messages.product_reference') }} *</label>
                    <input type="text" name="reference" required class="w-full border rounded p-2">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">{{ __('messages.product_price') }} *</label>
                    <input type="number" step="0.01" name="price" required class="w-full border rounded p-2">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">{{ __('messages.supplier') }} *</label>
                    <select name="supplier_id" required class="w-full border rounded p-2">
                        <option value="">{{ __('messages.select_supplier') }}</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="closeProductModal()"
                        class="px-4 py-2 text-gray-600">{{ __('messages.cancel') }}</button>
                    <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded">{{ __('messages.save') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- JAVASCRIPT LOGIC                           -->
    <!-- ========================================== -->
    <script>
        // --- 1. STATE & INITIALIZATION ---
        let rowIndex = 0;
        let allProductOptions = [];

        function cacheProductOptions() {
            allProductOptions = [];
            @foreach ($products as $product)
                allProductOptions.push({
                    value: String("{{ $product->id }}"), // Force to String
                    text: "{{ $product->reference }}",
                    pcs: "{{ $product->pcs_cts }}",
                    cbm: "{{ $product->unit_cbm }}",
                    gw: "{{ $product->unit_gw }}",
                    nw: "{{ $product->unit_nw }}"
                });
            @endforeach
        }

        document.addEventListener('DOMContentLoaded', function() {
            cacheProductOptions();
            addProductRow(); // Add initial empty row
        });

        // --- 2. TABLE UTILITIES ---
        function updateProductDropdowns() {
            let selectedIds = [];
            document.querySelectorAll('.product-select').forEach(function(select) {
                if (select.value) selectedIds.push(select.value);
            });

            document.querySelectorAll('.product-select').forEach(function(currentSelect) {
                let currentValue = currentSelect.value;
                currentSelect.innerHTML = '<option value="">{{ __('messages.product') }}</option>';

                allProductOptions.forEach(function(opt) {
                    if (!selectedIds.includes(opt.value) || opt.value === currentValue) {
                        let newOption = document.createElement('option');
                        newOption.value = opt.value;
                        newOption.text = opt.text;
                        newOption.setAttribute('data-pcs', opt.pcs);
                        newOption.setAttribute('data-cbm', opt.cbm);
                        newOption.setAttribute('data-gw', opt.gw);
                        newOption.setAttribute('data-nw', opt.nw);
                        if (opt.value === currentValue) newOption.selected = true;
                        currentSelect.appendChild(newOption);
                    }
                });
            });
        }

        function addProductRow() {
            let tbody = document.getElementById('products-table-body');
            let row = `
                <tr class="block lg:table-row border-b lg:border-0 mb-4 lg:mb-0 p-3 lg:p-0 rounded-lg lg:rounded-none">
                    
                    <!-- PRODUCT SELECT -->
                    <td class="block lg:table-cell border-0 lg:border p-2" data-label="{{ __('messages.product') }}">
                        <select name="products[${rowIndex}][id]" class="w-full border rounded p-2 product-select" onchange="handleProductChange(this)">
                            <option value="">{{ __('messages.product') }}</option>
                        </select>
                    </td>
                    
                    <!-- CTN -->
                    <td class="block lg:table-cell border-0 lg:border p-2" data-label="{{ __('messages.ctn') }}">
                        <input type="number" name="products[${rowIndex}][ctn]" class="w-full border rounded p-2 ctn-input" oninput="calculateRowTotals(this.closest('tr'))">
                    </td>
                    
                    <!-- PCS/CTS (Now an Input) -->
                    <td class="block lg:table-cell border-0 lg:border p-2" data-label="{{ __('messages.pcs_cts') }}">
                        <input type="number" name="products[${rowIndex}][pcs_cts]" class="w-full border rounded p-2 pcs-input" oninput="calculateRowTotals(this.closest('tr'))">
                    </td>
                    
                    <!-- UNIT CBM (Now an Input) -->
                    <td class="block lg:table-cell border-0 lg:border p-2" data-label="{{ __('messages.unit_cbm') }}">
                        <input type="number" step="0.001" name="products[${rowIndex}][unit_cbm]" class="w-full border rounded p-2 cbm-input" oninput="calculateRowTotals(this.closest('tr'))">
                    </td>
                    
                    <!-- UNIT GW (Now an Input) -->
                    <td class="block lg:table-cell border-0 lg:border p-2" data-label="{{ __('messages.unit_gw') }}">
                        <input type="number" step="0.01" name="products[${rowIndex}][unit_gw]" class="w-full border rounded p-2 gw-input" oninput="calculateRowTotals(this.closest('tr'))">
                    </td>
                    
                    <!-- UNIT NW (Now an Input) -->
                    <td class="block lg:table-cell border-0 lg:border p-2" data-label="{{ __('messages.unit_nw') }}">
                        <input type="number" step="0.01" name="products[${rowIndex}][unit_nw]" class="w-full border rounded p-2 nw-input" oninput="calculateRowTotals(this.closest('tr'))">
                    </td>
                    
                    <!-- TOTALS (Read-only text) -->
                    <td class="block lg:table-cell border-0 lg:border p-2 total-cbm-cell font-bold" data-label="{{ __('messages.total_cbm') }}">0.000</td>
                    <td class="block lg:table-cell border-0 lg:border p-2 total-gw-cell font-bold" data-label="{{ __('messages.total_gw') }}">0.00</td>
                    <td class="block lg:table-cell border-0 lg:border p-2 total-nw-cell font-bold" data-label="{{ __('messages.total_nw') }}">0.00</td>
                    
                    <!-- ACTIONS -->
                    <td class="block lg:table-cell border-0 lg:border p-2 text-center" data-label="">
                        <button type="button" onclick="this.closest('tr').remove(); updateProductDropdowns();" class="text-red-600 hover:text-red-800">✕</button>
                    </td>
                </tr>
            `;
            tbody.insertAdjacentHTML('beforeend', row);
            rowIndex++;
            updateProductDropdowns();
        }

        function handleProductChange(select) {
            let row = select.closest('tr');
            let option = select.options[select.selectedIndex];

            // Fill the editable inputs with the product data
            row.querySelector('.pcs-input').value = option.dataset.pcs || '';
            row.querySelector('.cbm-input').value = option.dataset.cbm || '';
            row.querySelector('.gw-input').value = option.dataset.gw || '';
            row.querySelector('.nw-input').value = option.dataset.nw || '';

            calculateRowTotals(row);
            updateProductDropdowns();
        }

        function calculateRowTotals(row) {
            // Read values from the INPUTS, not the innerText of the cells
            let ctn = parseFloat(row.querySelector('.ctn-input').value) || 0;
            let cbm = parseFloat(row.querySelector('.cbm-input').value) || 0;
            let gw = parseFloat(row.querySelector('.gw-input').value) || 0;
            let nw = parseFloat(row.querySelector('.nw-input').value) || 0;

            // Write the calculated totals back to the TD elements
            row.querySelector('.total-cbm-cell').innerText = (ctn * cbm).toFixed(3);
            row.querySelector('.total-gw-cell').innerText = (ctn * gw).toFixed(2);
            row.querySelector('.total-nw-cell').innerText = (ctn * nw).toFixed(2);
        }

        document.getElementById('add-product-row').addEventListener('click', addProductRow);

        // --- 3. MODAL LOGIC & AJAX SUBMISSIONS ---

        // CLIENT MODAL
        function openClientModal() {
            document.getElementById('clientModal').classList.remove('hidden');
        }

        function closeClientModal() {
            document.getElementById('clientModal').classList.add('hidden');
            document.getElementById('clientAjaxForm').reset();
        }

        document.getElementById('clientAjaxForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            fetch("{{ route('ajax.clients.store') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json'
                    }
                })
                .then(async response => {
                    let data = await response.json();
                    if (!response.ok) {
                        let errorMsg = data.errors ? Object.values(data.errors).flat().join('\n') :
                            'Validation failed';
                        throw new Error(errorMsg);
                    }
                    return data;
                })
                .then(data => {
                    if (data.success) {
                        let clientSelect = document.getElementById('client_id');
                        let newOption = new Option(data.client.name, data.client.id, true, true);
                        clientSelect.add(newOption);
                        closeClientModal();
                    }
                })
                .catch(error => {
                    alert(error.message);
                });
        });

        // PRODUCT MODAL
        function openProductModal() {
            document.getElementById('productModal').classList.remove('hidden');
        }

        function closeProductModal() {
            document.getElementById('productModal').classList.add('hidden');
            document.getElementById('productAjaxForm').reset();
        }

        document.getElementById('productAjaxForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            fetch("{{ route('ajax.products.store') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json'
                    }
                })
                .then(async response => {
                    let data = await response.json();
                    if (!response.ok) {
                        let errorMsg = data.errors ? Object.values(data.errors).flat().join('\n') :
                            'Validation failed';
                        throw new Error(errorMsg);
                    }
                    return data;
                })
                .then(data => {
                    if (data.success) {
                        // 1. Add the new product to our local JS cache (Force ID to String)
                        let newProductData = {
                            value: String(data.product.id),
                            text: data.product.reference,
                            pcs: data.product.pcs_cts || '',
                            cbm: data.product.unit_cbm || '',
                            gw: data.product.unit_gw || '',
                            nw: data.product.unit_nw || ''
                        };
                        allProductOptions.push(newProductData);

                        // 2. Update ALL existing dropdowns in the table to include this new product
                        updateProductDropdowns();

                        // 3. Add a brand new empty row for this product
                        addProductRow();

                        // 4. Find the row we just created (it's the last one)
                        let rows = document.querySelectorAll('#products-table-body tr');
                        let lastRow = rows[rows.length - 1];
                        let select = lastRow.querySelector('.product-select');

                        // 5. Select the new product in the dropdown
                        select.value = String(data.product.id);

                        // 6. Trigger the change event so the CTN, PCS, CBM inputs get filled
                        select.dispatchEvent(new Event('change'));

                        // 7. Close the modal and reset it
                        closeProductModal();
                    }
                })
                .catch(error => {
                    alert(error.message);
                });
        });
    </script>
</x-app-layout>
