<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
            {{ __('messages.create_receiving_note') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <h1 class="text-xl md:text-2xl font-bold mb-6">{{ __('messages.create_receiving_note') }}</h1>

        <form action="{{ route('receiving-notes.store') }}" method="POST">
            @csrf

            <div class="bg-white shadow rounded-lg p-4 md:p-6">

                <!-- HEADER INFO -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="min-w-0">
                        <label
                            class="block text-sm font-bold text-gray-700 mb-2">{{ __('messages.proforma_invoices') }}</label>
                        <select name="proforma_invoice_id" id="proforma_invoice_id"
                            class="border rounded w-full py-2 px-3">
                            <option value="">—</option>
                            @foreach ($proformaInvoices as $invoice)
                                <option value="{{ $invoice->id }}" data-container="{{ $invoice->container_no }}"
                                    data-date="{{ $invoice->date }}"
                                    {{ old('proforma_invoice_id') == $invoice->id ? 'selected' : '' }}>
                                    #{{ $invoice->id }} — {{ $invoice->client?->name }} ({{ $invoice->date }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="min-w-0">
                        <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('messages.date') }}</label>
                        <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}"
                            class="border rounded w-full py-2 px-3">
                    </div>

                    <div class="min-w-0">
                        <label
                            class="block text-sm font-bold text-gray-700 mb-2">{{ __('messages.container_no') }}</label>
                        <input type="text" name="container_no" id="container_no" value="{{ old('container_no') }}"
                            class="border rounded w-full py-2 px-3">
                    </div>

                    <div class="min-w-0">
                        <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('messages.seal_no') }}</label>
                        <input type="text" name="seal_no" id="seal_no" value="{{ old('seal_no') }}"
                            class="border rounded w-full py-2 px-3">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('messages.note') }}</label>
                        <textarea name="note" rows="2" class="border rounded w-full py-2 px-3">{{ old('note') }}</textarea>
                    </div>
                </div>

                <!-- PRODUCTS -->
                <div class="mt-10">
                    <h2 class="text-lg md:text-xl font-bold mb-4">{{ __('messages.products') }}</h2>

                    <table class="products-table w-full border border-gray-300">
                        <thead class="hidden lg:table-header-group bg-gray-100">
                            <tr>
                                <th class="border p-2">{{ __('messages.product') }}</th>
                                <th class="border p-2">{{ __('messages.supplier') }}</th>
                                <th class="border p-2">{{ __('messages.ctn') }}</th>
                                <th class="border p-2">{{ __('messages.pcs_cts') }}</th>
                                <th class="border p-2">{{ __('messages.qty') }}</th>
                                <th class="border p-2">{{ __('messages.total_cbm') }}</th>
                                <th class="border p-2">{{ __('messages.total_gw') }}</th>
                                <th class="border p-2">{{ __('messages.total_nw') }}</th>
                                <th class="border p-2">{{ __('messages.note') }}</th>
                                <th class="border p-2">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody id="products-table-body" class="block lg:table-row-group"></tbody>
                    </table>

                </div>

                <!-- TOTALS -->
                <div class="mt-6 bg-gray-100 rounded-lg p-4 font-bold text-gray-800 text-sm flex flex-wrap gap-6">
                    <span>{{ __('messages.total') }} CTN: <span id="total-ctn">0</span></span>
                    <span>{{ __('messages.total') }} QTY: <span id="total-qty">0</span></span>
                    <span>{{ __('messages.total_cbm') }}: <span id="total-cbm">0.000</span></span>
                    <span>{{ __('messages.total_gw') }}: <span id="total-gw">0.00</span></span>
                    <span>{{ __('messages.total_nw') }}: <span id="total-nw">0.00</span></span>
                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 mt-6">
                    <a href="{{ route('receiving-notes.index') }}"
                        class="text-gray-600 hover:text-gray-800 text-center sm:text-left py-2">{{ __('messages.cancel') }}</a>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full sm:w-auto">
                        {{ __('messages.save') }}
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
        const proformaData = {
            @foreach ($proformaInvoices as $invoice)
                "{{ $invoice->id }}": {
                    container: "{{ $invoice->container_no ?? '' }}",
                    date: "{{ $invoice->date ?? '' }}",
                    products: [
                        @foreach ($invoice->products as $p)
                            {
                                id: {{ $p->id }},
                                ctn: {{ $p->pivot->ctn ?? 0 }},
                                note: ""
                            },
                        @endforeach
                    ]
                },
            @endforeach
        };

        const productsData = [
            @foreach ($products as $p)
                {
                    id: {{ $p->id }},
                    reference: "{{ addslashes($p->reference) }}",
                    pcs: {{ $p->pcs_cts ?? 0 }},
                    cbm: {{ $p->unit_cbm ?? 0 }},
                    gw: {{ $p->unit_gw ?? 0 }},
                    nw: {{ $p->unit_nw ?? 0 }},
                    supplier: "{{ addslashes($p->suppliers->first()?->name ?? '—') }}"
                },
            @endforeach
        ];

        let rowIndex = 0;
        let allOptions = productsData.map(p => ({
            value: String(p.id),
            text: p.reference
        }));

        function updateDropdowns() {
            let selectedIds = [];
            document.querySelectorAll('.product-select').forEach(sel => {
                if (sel.value) selectedIds.push(sel.value);
            });

            document.querySelectorAll('.product-select').forEach(sel => {
                let curr = sel.value;
                sel.innerHTML = '<option value="">{{ __('messages.product') }}</option>';

                allOptions.forEach(opt => {
                    if (!selectedIds.includes(opt.value) || opt.value === curr) {
                        let o = document.createElement('option');
                        o.value = opt.value;
                        o.text = opt.text;
                        if (opt.value === curr) o.selected = true;
                        sel.appendChild(o);
                    }
                });
            });
        }

        function addRow(prefill = null) {
            let tbody = document.getElementById('products-table-body');
            let row = document.createElement('tr');
            row.className = 'block lg:table-row border-b lg:border-0 mb-4 lg:mb-0 p-3 lg:p-0 rounded-lg lg:rounded-none';

            let currentIndex = rowIndex;

            row.innerHTML = `
                <td class="block lg:table-cell border-0 lg:border p-2" data-label="{{ __('messages.product') }}">
                    <select name="products[${currentIndex}][id]" class="w-full border rounded p-2 product-select" onchange="handleChange(this)">
                        <option value="">{{ __('messages.product') }}</option>
                    </select>
                </td>
                <td class="block lg:table-cell border-0 lg:border p-2 text-sm text-gray-700 supplier-cell" data-label="{{ __('messages.supplier') }}">—</td>
                <td class="block lg:table-cell border-0 lg:border p-2" data-label="{{ __('messages.ctn') }}">
                    <input type="number" name="products[${currentIndex}][ctn]" min="0" value="0" class="w-full border rounded p-2 ctn-input" oninput="calculateTotals()">
                </td>
                <td class="block lg:table-cell border-0 lg:border p-2 text-sm text-center pcs-cell" data-label="{{ __('messages.pcs_cts') }}">-</td>
                <td class="block lg:table-cell border-0 lg:border p-2 text-sm text-center qty-cell" data-label="{{ __('messages.qty') }}">0</td>
                <td class="block lg:table-cell border-0 lg:border p-2 text-sm text-center cbm-cell" data-label="{{ __('messages.total_cbm') }}">-</td>
                <td class="block lg:table-cell border-0 lg:border p-2 text-sm text-center gw-cell" data-label="{{ __('messages.total_gw') }}">-</td>
                <td class="block lg:table-cell border-0 lg:border p-2 text-sm text-center nw-cell" data-label="{{ __('messages.total_nw') }}">-</td>
                <td class="block lg:table-cell border-0 lg:border p-2" data-label="{{ __('messages.note') }}">
                    <input type="text" name="products[${currentIndex}][note]" class="w-full border rounded p-2">
                </td>
                <td class="block lg:table-cell border-0 lg:border p-2 text-center" data-label="">
                    <button type="button" onclick="this.closest('tr').remove(); updateDropdowns(); calculateTotals();" class="text-red-600 hover:text-red-800">✕</button>
                </td>
            `;
            tbody.appendChild(row);

            // 1. First, populate the dropdown
            updateDropdowns();

            // 2. THEN set the prefill values
            if (prefill) {
                let sel = row.querySelector('.product-select');
                sel.value = String(prefill.id);
                row.querySelector('.ctn-input').value = prefill.ctn;
                row.querySelector('[name$="[note]"]').value = prefill.note ?? '';
                handleChange(sel);
            }

            rowIndex++;
        }

        function handleChange(select) {
            let row = select.closest('tr');
            let id = select.value;
            let p = productsData.find(x => String(x.id) === String(id));
            if (!p) {
                row.querySelector('.supplier-cell').innerText = '—';
                row.querySelector('.pcs-cell').innerText = '-';
                row.querySelector('.qty-cell').innerText = '0';
                row.querySelector('.cbm-cell').innerText = '-';
                row.querySelector('.gw-cell').innerText = '-';
                row.querySelector('.nw-cell').innerText = '-';
            } else {
                row.querySelector('.supplier-cell').innerText = p.supplier;
                row.querySelector('.pcs-cell').innerText = p.pcs ?? '-';
            }
            updateDropdowns();
            calculateTotals();
        }

        function calculateTotals() {
            let tCtn = 0,
                tQty = 0,
                tCbm = 0,
                tGw = 0,
                tNw = 0;
            document.querySelectorAll('#products-table-body tr').forEach(row => {
                let sel = row.querySelector('.product-select');
                let id = sel.value;
                let p = productsData.find(x => String(x.id) === String(id));
                let ctn = parseFloat(row.querySelector('.ctn-input').value) || 0;

                if (p) {
                    let qty = ctn * (p.pcs || 0);
                    let cbm = ctn * (p.cbm || 0);
                    let gw = ctn * (p.gw || 0);
                    let nw = ctn * (p.nw || 0);

                    row.querySelector('.qty-cell').innerText = qty;
                    row.querySelector('.cbm-cell').innerText = cbm.toFixed(3);
                    row.querySelector('.gw-cell').innerText = gw.toFixed(2);
                    row.querySelector('.nw-cell').innerText = nw.toFixed(2);

                    tCtn += ctn;
                    tQty += qty;
                    tCbm += cbm;
                    tGw += gw;
                    tNw += nw;
                }
            });
            document.getElementById('total-ctn').innerText = tCtn;
            document.getElementById('total-qty').innerText = tQty;
            document.getElementById('total-cbm').innerText = tCbm.toFixed(3);
            document.getElementById('total-gw').innerText = tGw.toFixed(2);
            document.getElementById('total-nw').innerText = tNw.toFixed(2);
        }

        document.getElementById('proforma_invoice_id')?.addEventListener('change', function() {
            let invoiceId = this.value;
            let opt = this.options[this.selectedIndex];

            if (opt.dataset.container) document.getElementById('container_no').value = opt.dataset.container;
            if (opt.dataset.date) document.getElementById('date').value = opt.dataset.date;

            document.getElementById('products-table-body').innerHTML = '';
            rowIndex = 0;

            if (invoiceId && proformaData[invoiceId]) {
                let invProducts = proformaData[invoiceId].products;
                if (invProducts.length > 0) {
                    invProducts.forEach(function(p) {
                        addRow({
                            id: p.id,
                            ctn: p.ctn,
                            note: p.note
                        });
                    });
                } else {
                    addRow();
                }
            } else {
                addRow();
            }
        });

        document.getElementById('add-product-row').addEventListener('click', () => addRow());

        document.addEventListener('DOMContentLoaded', () => {
            addRow();
        });
    </script>
</x-app-layout>
