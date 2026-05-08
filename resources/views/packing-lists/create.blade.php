@extends('layouts.app')

@section('content')

<div class="container mx-auto px-4 py-6">

    <h1 class="text-2xl font-bold mb-6">
        Create Packing List
    </h1>

    <form action="{{ route('packing-lists.store') }}"
          method="POST">

        @csrf

        <div class="bg-white shadow rounded-lg p-6">

            <!-- CLIENT -->
            <div class="mb-6">

                <label class="block text-sm font-bold text-gray-700 mb-2">
                    Client
                </label>

                <select name="client_id"
                        id="client_id"
                        class="border rounded w-full py-2 px-3">

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

            <!-- SHIPPING INFO -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Port Of Loading
                    </label>

                    <input type="text"
                           name="port_of_loading"
                           class="border rounded w-full py-2 px-3">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Port Of Discharge
                    </label>

                    <input type="text"
                           name="port_of_discharge"
                           class="border rounded w-full py-2 px-3">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Date
                    </label>

                    <input type="date"
                           name="date"
                           class="border rounded w-full py-2 px-3">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Container No
                    </label>

                    <input type="text"
                           name="container_no"
                           class="border rounded w-full py-2 px-3">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Seal No
                    </label>

                    <input type="text"
                           name="seal_no"
                           class="border rounded w-full py-2 px-3">
                </div>

            </div>

            <!-- PRODUCTS TABLE -->
            <div class="mt-10">

                <h2 class="text-xl font-bold mb-4">
                    Products
                </h2>

                <table class="w-full border border-gray-300">

                    <thead class="bg-gray-100">

                        <tr>

                            <th class="border p-2">
                                Product
                            </th>

                            <th class="border p-2">
                                CTN
                            </th>

                            <th class="border p-2">
                                PCS/CTS
                            </th>

                            <th class="border p-2">
                                UNIT CBM
                            </th>

                            <th class="border p-2">
                                UNIT GW
                            </th>

                            <th class="border p-2">
                                UNIT NW
                            </th>

                            <th class="border p-2">
                                TOTAL CBM
                            </th>

                            <th class="border p-2">
                                TOTAL GW
                            </th>

                            <th class="border p-2">
                                TOTAL NW
                            </th>

                        </tr>

                    </thead>

                    <tbody id="products-table-body">

                        <tr>

                            <!-- PRODUCT -->
                            <td class="border p-2">

                                <select name="products[0][id]"
                                        class="w-full border rounded p-2">

                                    <option value="">
                                        Select Product
                                    </option>

                                    @foreach($products as $product)

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

                            <!-- CTN -->
                            <td class="border p-2">

                                <input type="number"
                                    name="products[0][ctn]"
                                    class="w-full border rounded p-2">

                            </td>

                            <!-- PCS/CTS -->
                            <td class="border p-2 pcs-cell">-</td>

                            <!-- UNIT CBM -->
                            <td class="border p-2 cbm-cell">-</td>

                            <!-- UNIT GW -->
                            <td class="border p-2 gw-cell">-</td>

                            <!-- UNIT NW -->
                            <td class="border p-2 nw-cell">-</td>

                            <td class="border p-2 total-cbm-cell">
                                -
                            </td>

                            <td class="border p-2 total-gw-cell">
                                -
                            </td>

                            <td class="border p-2 total-nw-cell">
                                -
                            </td>

                        </tr>

                    </tbody>

                </table>
                <button type="button"
                        id="add-product-row"
                        class="mt-4 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">

                    Add Product

                </button>

            </div>

            <button type="submit"
                    class="mt-6 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">

                Save Packing List

            </button>

        </div>

    </form>

</div>

<script>

    let rowIndex = 1;

    document.getElementById('add-product-row')
        .addEventListener('click', function () {

            let tbody = document.getElementById('products-table-body');

            let row = `
                <tr>

                    <td class="border p-2">

                        <select name="products[${rowIndex}][id]"
                                class="w-full border rounded p-2">

                            <option value="">
                                Select Product
                            </option>

                            @foreach($products as $product)

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

                    <td class="border p-2">

                        <input type="number"
                            name="products[${rowIndex}][ctn]"
                            class="w-full border rounded p-2">

                    </td>

                    <td class="border p-2 pcs-cell">-</td>

                    <td class="border p-2 cbm-cell">-</td>

                    <td class="border p-2 gw-cell">-</td>

                    <td class="border p-2 nw-cell">-</td>

                    <td class="border p-2 total-cbm-cell">
                        -
                    </td>

                    <td class="border p-2 total-gw-cell">
                        -
                    </td>

                    <td class="border p-2 total-nw-cell">
                        -
                    </td>

                </tr>
            `;

            tbody.insertAdjacentHTML('beforeend', row);

            rowIndex++;

        });

</script>

<script>

    document.addEventListener('change', function (e) {
        if (e.target.matches('select[name*="[id]"]')) {
            
            let select = e.target;
            
            let row = select.closest('tr');
            
            let option = select.options[select.selectedIndex];

            row.querySelector('.pcs-cell').innerText =
                option.dataset.pcs || '-';

            row.querySelector('.cbm-cell').innerText =
                option.dataset.cbm || '-';

            row.querySelector('.gw-cell').innerText =
                option.dataset.gw || '-';

            row.querySelector('.nw-cell').innerText =
                option.dataset.nw || '-';
        }

        calculateRowTotals(row);

    });

</script>

<script>

    function calculateRowTotals(row)
    {
        let ctnInput = row.querySelector('input[name*="[ctn]"]');

        let ctn = parseFloat(ctnInput.value) || 0;

        let cbm = parseFloat(
            row.querySelector('.cbm-cell').innerText
        ) || 0;

        let gw = parseFloat(
            row.querySelector('.gw-cell').innerText
        ) || 0;

        let nw = parseFloat(
            row.querySelector('.nw-cell').innerText
        ) || 0;

        row.querySelector('.total-cbm-cell').innerText =
            (ctn * cbm).toFixed(3);

        row.querySelector('.total-gw-cell').innerText =
            (ctn * gw).toFixed(2);

        row.querySelector('.total-nw-cell').innerText =
            (ctn * nw).toFixed(2);
    }

</script>

<script>

    document.addEventListener('input', function (e) {

        if (e.target.matches('input[name*="[ctn]"]')) {

            let row = e.target.closest('tr');

            calculateRowTotals(row);
        }

    });

</script>

@endsection