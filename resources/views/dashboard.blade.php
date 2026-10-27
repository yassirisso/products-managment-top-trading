<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
            {{ __('messages.dashboard') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <!-- HEADER: RANGE FILTER + QUICK ACTIONS -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-4">

            <div class="min-w-0">
                <h1 class="text-xl md:text-2xl font-bold text-gray-800">
                    {{ __('messages.dashboard') }}
                </h1>
                <p class="text-gray-600 text-sm">
                    {{ __('messages.overview_text') }}
                </p>
            </div>

            <div
                class="flex flex-col sm:flex-row sm:flex-wrap items-stretch sm:items-center gap-2 w-full md:w-auto min-w-0">

                <form action="{{ route('dashboard') }}" method="GET" class="w-full sm:w-auto">
                    <select name="range" onchange="this.form.submit()"
                        class="w-full sm:w-auto px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <option value="this_month" {{ $range === 'this_month' ? 'selected' : '' }}>
                            {{ __('messages.this_month') }}</option>
                        <option value="last_month" {{ $range === 'last_month' ? 'selected' : '' }}>
                            {{ __('messages.last_month') }}</option>
                        <option value="last_3_months" {{ $range === 'last_3_months' ? 'selected' : '' }}>
                            {{ __('messages.last_3_months') }}</option>
                        <option value="this_year" {{ $range === 'this_year' ? 'selected' : '' }}>
                            {{ __('messages.this_year') }}</option>
                    </select>
                </form>

                @can('create clients')
                    <a href="{{ route('clients.create') }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm text-center whitespace-nowrap">
                        {{ __('messages.quick_client') }}
                    </a>
                @endcan

                @can('create suppliers')
                    <a href="{{ route('suppliers.create') }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm text-center whitespace-nowrap">
                        {{ __('messages.quick_supplier') }}
                    </a>
                @endcan

                @can('create commercial invoices')
                    <a href="{{ route('commercial-invoices.create') }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm text-center whitespace-nowrap">
                        {{ __('messages.quick_commercial') }}
                    </a>
                @endcan

            </div>
        </div>

        <!-- KPI ROW -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 md:gap-4 mb-6">

            <!-- CLIENTS -->
            <a href="{{ route('clients.index') }}"
                class="bg-white shadow-sm rounded-lg p-3 md:p-4 hover:shadow-md hover:ring-2 hover:ring-blue-200 transition">
                <p class="text-xs uppercase tracking-wide text-gray-500">{{ __('messages.clients') }}</p>
                <p class="text-xl md:text-2xl font-bold text-gray-800 mt-1">{{ $totalClients }}</p>
            </a>

            <!-- SUPPLIERS -->
            <a href="{{ route('suppliers.index') }}"
                class="bg-white shadow-sm rounded-lg p-3 md:p-4 hover:shadow-md hover:ring-2 hover:ring-blue-200 transition">
                <p class="text-xs uppercase tracking-wide text-gray-500">{{ __('messages.suppliers') }}</p>
                <p class="text-xl md:text-2xl font-bold text-gray-800 mt-1">{{ $totalSuppliers }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    {{ $totalShops }} {{ __('messages.shops') }} · {{ $totalFactories }}
                    {{ __('messages.factories') }}
                </p>
            </a>

            <!-- PRODUCTS -->
            <a href="{{ route('products.index') }}"
                class="bg-white shadow-sm rounded-lg p-3 md:p-4 hover:shadow-md hover:ring-2 hover:ring-blue-200 transition">
                <p class="text-xs uppercase tracking-wide text-gray-500">{{ __('messages.products') }}</p>
                <p class="text-xl md:text-2xl font-bold text-gray-800 mt-1">{{ $totalProducts }}</p>
            </a>

            <!-- INVOICES (PERIOD) -->
            <a href="{{ route('commercial-invoices.index') }}"
                class="bg-white shadow-sm rounded-lg p-3 md:p-4 hover:shadow-md hover:ring-2 hover:ring-blue-200 transition">
                <p class="text-xs uppercase tracking-wide text-gray-500">
                    {{ __('messages.invoices_period') }} ({{ str_replace('_', ' ', $range) }})
                </p>
                <p class="text-xl md:text-2xl font-bold text-gray-800 mt-1">{{ $invoicesThisMonth }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    {{ __('messages.total') }} {{ number_format($invoicesThisMonthTotal, 2) }}
                </p>
            </a>

            <!-- SHIPPING FEES (PERIOD) -->
            <a href="{{ route('shipping-companies.index') }}"
                class="bg-white shadow-sm rounded-lg p-3 md:p-4 hover:shadow-md hover:ring-2 hover:ring-blue-200 transition">
                <p class="text-xs uppercase tracking-wide text-gray-500">{{ __('messages.shipping_fees') }}</p>
                <p class="text-xl md:text-2xl font-bold text-gray-800 mt-1">
                    {{ number_format($shippingFeesRmb, 2) }} <span class="text-sm">RMB</span>
                </p>
                <p class="text-xs text-gray-500 mt-1">
                    {{ number_format($shippingFeesUsd, 2) }} USD
                </p>
            </a>

            <!-- OUTSTANDING -->
            <a href="{{ route('products.index') }}"
                class="bg-white shadow-sm rounded-lg p-3 md:p-4 hover:shadow-md hover:ring-2 hover:ring-red-200 transition">
                <p class="text-xs uppercase tracking-wide text-gray-500">{{ __('messages.outstanding_payments') }}</p>
                <p class="text-xl md:text-2xl font-bold text-red-600 mt-1">
                    {{ number_format($outstandingAmount, 2) }}
                </p>
                <p class="text-xs text-gray-500 mt-1">
                    {{ __('messages.unpaid_partial') }}
                </p>
            </a>
        </div>

        <!-- CHARTS ROW 1 -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
            <div class="bg-white shadow-sm rounded-lg p-4 lg:col-span-2">
                <h3 class="text-base md:text-lg font-semibold text-gray-800 mb-3">
                    {{ __('messages.monthly_sales_trend') }}</h3>
                <div style="position: relative; height: 260px;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-4">
                <h3 class="text-base md:text-lg font-semibold text-gray-800 mb-3">
                    {{ __('messages.supplier_type_split') }}</h3>
                <div style="position: relative; height: 260px;">
                    <canvas id="supplierTypeChart"></canvas>
                </div>
            </div>
        </div>

        <!-- CHARTS ROW 2 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
            <div class="bg-white shadow-sm rounded-lg p-4">
                <h3 class="text-base md:text-lg font-semibold text-gray-800 mb-3">{{ __('messages.top_5_clients') }}
                </h3>
                <div style="position: relative; height: 260px;">
                    <canvas id="topClientsChart"></canvas>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-4">
                <h3 class="text-base md:text-lg font-semibold text-gray-800 mb-3">{{ __('messages.top_5_suppliers') }}
                </h3>
                <div style="position: relative; height: 260px;">
                    <canvas id="topSuppliersChart"></canvas>
                </div>
            </div>
        </div>

        <!-- RECENT LISTS ROW -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
            <!-- RECENT INVOICES -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-base md:text-lg font-semibold text-gray-800">{{ __('messages.recent_invoices') }}
                    </h3>
                    <a href="{{ route('commercial-invoices.index') }}"
                        class="text-blue-600 hover:underline text-sm whitespace-nowrap">
                        {{ __('messages.view_all') }}
                    </a>
                </div>

                <div class="hidden lg:block">
                    <table class="min-w-full divide-y divide-gray-200">
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentInvoices as $invoice)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <a href="{{ route('commercial-invoices.show', $invoice) }}"
                                            class="text-blue-600 hover:underline">
                                            #{{ $invoice->invoice_no ?? $invoice->id }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-gray-700">
                                        {{ $invoice->client?->name ?? '—' }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-gray-500 text-sm text-right">
                                        {{ $invoice->date }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-center text-gray-500">
                                        {{ __('messages.no_invoices_yet') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="lg:hidden divide-y divide-gray-200">
                    @forelse($recentInvoices as $invoice)
                        <div class="px-4 py-3 text-sm">
                            <div class="flex justify-between items-start gap-2">
                                <a href="{{ route('commercial-invoices.show', $invoice) }}"
                                    class="text-blue-600 hover:underline font-medium">
                                    #{{ $invoice->invoice_no ?? $invoice->id }}
                                </a>
                                <span class="text-gray-500 text-xs shrink-0">{{ $invoice->date }}</span>
                            </div>
                            <p class="text-gray-700 mt-1 break-words">{{ $invoice->client?->name ?? '—' }}</p>
                        </div>
                    @empty
                        <div class="px-4 py-3 text-center text-gray-500 text-sm">{{ __('messages.no_invoices_yet') }}
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- RECENT SHIPPING FEES -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-base md:text-lg font-semibold text-gray-800">
                        {{ __('messages.recent_shipping_fees') }}</h3>
                    <a href="{{ route('shipping-companies.index') }}"
                        class="text-blue-600 hover:underline text-sm whitespace-nowrap">
                        {{ __('messages.view_companies') }}
                    </a>
                </div>

                <div class="hidden lg:block">
                    <table class="min-w-full divide-y divide-gray-200">
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentFees as $fee)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap text-gray-700">
                                        {{ $fee->shippingCompany?->name ?? '—' }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-gray-500 text-sm">
                                        {{ $fee->commercialInvoice ? '#' . $fee->commercialInvoice->invoice_no : '—' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right font-medium">
                                        {{ number_format($fee->amount, 2) }} {{ $fee->currency }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-center text-gray-500">
                                        {{ __('messages.no_shipping_fees_yet') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="lg:hidden divide-y divide-gray-200">
                    @forelse($recentFees as $fee)
                        <div class="px-4 py-3 text-sm">
                            <div class="flex justify-between items-start gap-2">
                                <p class="text-gray-900 font-medium break-words">
                                    {{ $fee->shippingCompany?->name ?? '—' }}</p>
                                <p class="font-semibold shrink-0">{{ number_format($fee->amount, 2) }}
                                    {{ $fee->currency }}</p>
                            </div>
                            <p class="text-gray-500 text-xs mt-1">
                                {{ $fee->commercialInvoice ? '#' . $fee->commercialInvoice->invoice_no : '—' }}</p>
                        </div>
                    @empty
                        <div class="px-4 py-3 text-center text-gray-500 text-sm">
                            {{ __('messages.no_shipping_fees_yet') }}</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- ALERTS -->
        <div class="bg-white shadow-sm rounded-lg p-4">
            <h3 class="text-base md:text-lg font-semibold text-gray-800 mb-3">{{ __('messages.alerts_pending') }}</h3>
            @if (empty($alerts))
                <p class="text-gray-500 text-sm">{{ __('messages.all_clear') }}</p>
            @else
                <ul class="space-y-2">
                    @foreach ($alerts as $alert)
                        <li class="flex items-start gap-2 text-sm">
                            <span
                                class="inline-block w-2 h-2 rounded-full mt-2 shrink-0 {{ $alert['type'] === 'warning' ? 'bg-yellow-500' : 'bg-blue-500' }}"></span>
                            <span class="text-gray-700">{{ $alert['text'] }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <!-- CHART.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <script>
        const salesLabels = @json($salesLabels);
        const salesValues = @json($salesValues);
        const supplierTypeLabels = @json($supplierTypeLabels);
        const supplierTypeValues = @json($supplierTypeValues);
        const topClientLabels = @json($topClientLabels);
        const topClientValues = @json($topClientValues);
        const topSupplierLabels = @json($topSupplierLabels);
        const topSupplierValues = @json($topSupplierValues);

        // SALES TREND
        new Chart(document.getElementById('salesChart'), {
            type: 'bar',
            data: {
                labels: salesLabels,
                datasets: [{
                    label: "{{ __('messages.invoice_value') }}",
                    data: salesValues,
                    backgroundColor: 'rgba(59,130,246,0.6)',
                    borderColor: 'rgba(59,130,246,1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // SUPPLIER TYPE SPLIT
        new Chart(document.getElementById('supplierTypeChart'), {
            type: 'doughnut',
            data: {
                labels: supplierTypeLabels,
                datasets: [{
                    data: supplierTypeValues,
                    backgroundColor: ['#3b82f6', '#10b981']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // TOP CLIENTS
        new Chart(document.getElementById('topClientsChart'), {
            type: 'bar',
            data: {
                labels: topClientLabels,
                datasets: [{
                    label: "{{ __('messages.invoice_value') }}",
                    data: topClientValues,
                    backgroundColor: 'rgba(16,185,129,0.6)',
                    borderColor: 'rgba(16,185,129,1)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });

        // TOP SUPPLIERS
        new Chart(document.getElementById('topSuppliersChart'), {
            type: 'bar',
            data: {
                labels: topSupplierLabels,
                datasets: [{
                    label: "{{ __('messages.purchase_value') }}",
                    data: topSupplierValues,
                    backgroundColor: 'rgba(245,158,11,0.6)',
                    borderColor: 'rgba(245,158,11,1)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

</x-app-layout>
