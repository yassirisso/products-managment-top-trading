<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">

            Edit Packing List #{{ $packingList->id }}

        </h2>

    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <h1 class="text-xl md:text-2xl font-bold mb-6">

            Edit Packing List #{{ $packingList->id }}

        </h1>

        <form action="{{ route('packing-lists.update', $packingList) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="bg-white shadow rounded-lg p-4 md:p-6">

                <!-- CLIENT -->
                <div class="mb-6">

                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Client
                    </label>

                    <select name="client_id" id="client_id" class="border rounded w-full py-2 px-3">

                        <option value="">Select Client</option>

                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}"
                                {{ old('client_id', $packingList->client_id) == $client->id ? 'selected' : '' }}>

                                {{ $client->name }}

                            </option>
                        @endforeach

                    </select>

                    @error('client_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror

                </div>

                <!-- SHIPPING INFO -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div class="min-w-0">

                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Port Of Loading
                        </label>

                        <input type="text" name="port_of_loading"
                            value="{{ old('port_of_loading', $packingList->port_of_loading) }}"
                            class="border rounded w-full py-2 px-3">

                    </div>

                    <div class="min-w-0">

                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Port Of Discharge
                        </label>

                        <input type="text" name="port_of_discharge"
                            value="{{ old('port_of_discharge', $packingList->port_of_discharge) }}"
                            class="border rounded w-full py-2 px-3">

                    </div>

                    <div class="min-w-0">

                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Date
                        </label>

                        <input type="date" name="date"
                            value="{{ old('date', $packingList->date) }}"
                            class="border rounded w-full py-2 px-3">

                    </div>

                    <div class="min-w-0">

                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Container No
                        </label>

                        <input type="text" name="container_no"
                            value="{{ old('container_no', $packingList->container_no) }}"
                            class="border rounded w-full py-2 px-3">

                    </div>

                    <div class="min-w-0">

                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Seal No
                        </label>

                        <input type="text" name="seal_no"
                            value="{{ old('seal_no', $packingList->seal_no) }}"
                            class="border rounded w-full py-2 px-3">

                    </div>

                </div>

                <!-- PRODUCTS -->
                <div class="mt-10">

                    <h2 class="text-lg md:text-xl font-bold mb-4">

                        Products

                    </h2>

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

                            @php $rowIndex = 0; @endphp

                            @forelse ($packingList->products as $rowProduct)
                                <tr
                                    class="block lg:table-row border-b lg:border-0 mb-4 lg:mb-0 p-3 lg:p-0 rounded-lg lg:rounded-none">

                                    <!-- PRODUCT -->
                                    <td class="block lg:table-cell border-0 lg:border p-2" data-label="Product">

                                        <select name="products[{{ $rowIndex }}][id]"
                                            class="w-full border rounded p-2 product-select">

                                            <option value="">Select Product</option>

                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    data-pcs="{{ $product->pcs_cts }}"
                                                    data-cbm="{{ $product->unit_cbm }}"
                                                    data-gw="{{ $product->unit_gw }}"
                                                    data-nw="{{ $product->unit_nw }}"
                                                    {{ $product->id == $rowProduct->id ? 'selected' : '' }}>

                                                    {{ $product->reference }}

                                                </option>
                                            @endforeach

                                        </select>

                                    </td>

                                    <!-- CTN -->
                                    <td class="block lg:table-cell border-0 lg:border p-2" data-label="CTN">

                                        <input type="number"
                                            name="products[{{ $rowIndex }}][ctn]"
                                            value="{{ $rowProduct->pivot->ctn }}"
                                            class="w-full border rounded p-2 ctn-input">

                                    </td>

                                    <td class="block lg:table-cell border-0 lg:border p-2 pcs-cell" data-label="PCS/CTS">
                                        {{ $rowProduct->pcs_cts ?? '-' }}
                                    </td>
                                    <td class="block lg:table-cell border-0 lg:border p-2 cbm-cell" data-label="UNIT CBM">
                                        {{ $rowProduct->unit_cbm ?? '-' }}
                                    </td>
                                    <td class="block lg:table-cell border-0 lg:border p-2 gw-cell" data-label="UNIT GW">
                                        {{ $rowProduct->unit_gw ?? '-' }}
                                    </td>
                                    <td class="block lg:table-cell border-0 lg:border p-2 nw-cell" data-label="UNIT NW">
                                        {{ $rowProduct->unit_nw ?? '-' }}
                                    </td>
                                    <td class="block lg:table-cell border-0 lg:border p-2 total-cbm-cell"
                                        data-label="TOTAL CBM">
                                        {{ number_format(($rowProduct->pivot->ctn ?? 0) * ($rowProduct->unit_cbm ?? 0), 3) }}
                                    </td>
                                    <td class="block lg:table-cell border-0 lg:border p-2 total-gw-cell"
                                        data-label="TOTAL GW">
                                        {{ number_format(($rowProduct->pivot->ctn ?? 0) * ($rowProduct->unit_gw ?? 0), 2) }}
                                    </td>
                                    <td class="block lg:table-cell border-0 lg:border p-2 total-nw-cell"
                                        data-label="TOTAL NW">
                                        {{ number_format(($rowProduct->pivot->ctn ?? 0) * ($rowProduct->unit_nw ?? 0), 2) }}
                                    </td>

                                    <!-- DELETE -->
                                    <td class="block lg:table-cell border-0 lg:border p-2 text-center"
                                        data-label="">

                                        <button type="button" class="remove-row text-red-600 hover:text-red-800">

                                            ✕

                                        </button>

                                    </td>

                                </tr>

                                @php $rowIndex++; @endphp
                            @empty

                                <tr
                                    class="block lg:table-row border-b lg:border-0 mb-4 lg:mb-0 p-3 lg:p-0 rounded-lg lg:rounded-none">

                                    <!-- EMPTY ROW -->
                                    <td class="block lg:table-cell border-0 lg:border p-2" data-label="Product">

                                        <select name="products[0][id]"
                                            class="w-full border rounded p-2 product-select">

                                            <option value="">Select Product</option>

                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    data-pcs="{{ $product->pcs_cts }}"
                                                    data-cbm="{{ $product->unit_cbm }}"
                                                    data-gw="{{ $product->unit_gw }}"
                                                    data-nw="{{ $product->unit_nw }}">

                                                    {{ $product->reference }}

                                                </option>
                                            @endforeach

                                        </select>

                                    </td>

                                    <td class="block lg:table-cell border-0 lg:border p-2" data-label="CTN">
                                        <input type="number" name="products[0][ctn]"
                                            class="w-full border rounded p-2 ctn-input">
                                    </td>

                                    <td class="block lg:table-cell border-0 lg:border p-2 pcs-cell" data-label="PCS/CTS">-</td>
                                    <td class="block lg:table-cell border-0 lg:border p-2 cbm-cell" data-label="UNIT CBM">-</td>
                                    <td class="block lg:table-cell border-0 lg:border p-2 gw-cell" data-label="UNIT GW">-</td>
                                    <td class="block lg:table-cell border-0 lg:border p-2 nw-cell" data-label="UNIT NW">-</td>
                                    <td class="block lg:table-cell border-0 lg:border p-2 total-cbm-cell" data-label="TOTAL CBM">-</td>
                                    <td class="block lg:table-cell border-0 lg:border p-2 total-gw-cell" data-label="TOTAL GW">-</td>
                                    <td class="block lg:table-cell border-0 lg:border p-2 total-nw-cell" data-label="TOTAL NW">-</td>

                                    <td class="block lg:table-cell border-0 lg:border p-2 text-center" data-label="">
                                        <button type="button" class="remove-row text-red-600 hover:text-red-800">✕</button>
                                    </td>

                                </tr>

                                @php $rowIndex = 1; @endphp
                            @endforelse

                        </tbody>

                    </table>

                    <button type="button" id="add-product-row"
                        class="mt-4 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full sm:w-auto">

                        Add Product

                    </button>

                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 mt-6">

                    <a href="{{ route('packing-lists.show', $packingList) }}"
                        class="text-gray-600 hover:text-gray-800 text-center sm:text-left py-2">

                        Cancel

                    </a>

                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full sm:w-auto">

                        Update Packing List

                    </button>

                </div>

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
        let rowIndex = {{ $rowIndex }};

        // ADD ROW
        document.getElementById('add-product-row')
            .addEventListener('click', function() {

                let tbody = document.getElementById('products-table-body');

                let row = `
                    <tr class="block lg:table-row border-b lg:border-0 mb-4 lg:mb-0 p-3 lg:p-0 rounded-lg lg:rounded-none">

                        <td class="block lg:table-cell border-0 lg:border p-2" data-label="Product">
                            <select name="products[${rowIndex}][id]" class="w-full border rounded p-2 product-select">
                                <option value="">Select Product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        data-pcs="{{ $product->pcs_cts }}"
                                        data-cbm="{{ $product->unit_cbm }}"
                                        data-gw="{{ $product->unit_gw }}"
                                        data-nw="{{ $product->unit_nw }}">
                                        {{ $product->reference }}
                                    </option>
                                @endforeach
                            </select>
                        </td>

                        <td class="block lg:table-cell border-0 lg:border p-2" data-label="CTN">
                            <input type="number" name="products[${rowIndex}][ctn]" class="w-full border rounded p-2 ctn-input">
                        </td>

                        <td class="block lg:table-cell border-0 lg:border p-2 pcs-cell" data-label="PCS/CTS">-</td>
                        <td class="block lg:table-cell border-0 lg:border p-2 cbm-cell" data-label="UNIT CBM">-</td>
                        <td class="block lg:table-cell border-0 lg:border p-2 gw-cell" data-label="UNIT GW">-</td>
                        <td class="block lg:table-cell border-0 lg:border p-2 nw-cell" data-label="UNIT NW">-</td>
                        <td class="block lg:table-cell border-0 lg:border p-2 total-cbm-cell" data-label="TOTAL CBM">-</td>
                        <td class="block lg:table-cell border-0 lg:border p-2 total-gw-cell" data-label="TOTAL GW">-</td>
                        <td class="block lg:table-cell border-0 lg:border p-2 total-nw-cell" data-label="TOTAL NW">-</td>

                        <td class="block lg:table-cell border-0 lg:border p-2 text-center" data-label="">
                            <button type="button" class="remove-row text-red-600 hover:text-red-800">✕</button>
                        </td>

                    </tr>
                `;

                tbody.insertAdjacentHTML('beforeend', row);

                rowIndex++;

            });

        // PRODUCT CHANGE
        document.addEventListener('change', function(e) {

            if (e.target.classList.contains('product-select')) {

                let select = e.target;

                let row = select.closest('tr');

                let option = select.options[select.selectedIndex];

                row.querySelector('.pcs-cell').innerText = option.dataset.pcs || '-';
                row.querySelector('.cbm-cell').innerText = option.dataset.cbm || '-';
                row.querySelector('.gw-cell').innerText = option.dataset.gw || '-';
                row.querySelector('.nw-cell').innerText = option.dataset.nw || '-';

                calculateRowTotals(row);

            }

        });

        // CTN INPUT
        document.addEventListener('input', function(e) {

            if (e.target.classList.contains('ctn-input')) {

                let row = e.target.closest('tr');

                calculateRowTotals(row);

            }

        });

        // REMOVE ROW
        document.addEventListener('click', function(e) {

            if (e.target.classList.contains('remove-row')) {

                let rows = document.querySelectorAll('#products-table-body tr');

                if (rows.length > 1) {

                    e.target.closest('tr').remove();

                }

            }

        });

        // CALCULATE TOTALS
        function calculateRowTotals(row) {
            let ctn = parseFloat(row.querySelector('.ctn-input').value) || 0;
            let cbm = parseFloat(row.querySelector('.cbm-cell').innerText) || 0;
            let gw = parseFloat(row.querySelector('.gw-cell').innerText) || 0;
            let nw = parseFloat(row.querySelector('.nw-cell').innerText) || 0;

            row.querySelector('.total-cbm-cell').innerText = (ctn * cbm).toFixed(3);
            row.querySelector('.total-gw-cell').innerText = (ctn * gw).toFixed(2);
            row.querySelector('.total-nw-cell').innerText = (ctn * nw).toFixed(2);
        }
    </script>

</x-app-layout>