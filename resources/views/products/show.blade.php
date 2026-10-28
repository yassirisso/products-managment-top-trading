<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Product Information -->
            <div class="bg-white rounded-lg shadow">
                <div class="border-b px-4 md:px-6 py-4">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
                            {{ __('messages.product_details') }}</h2>
                        <div class="flex gap-2">
                            <a href="{{ route('products.edit', $product->id) }}"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">{{ __('messages.edit') }}</a>
                            <a href="{{ route('products.index') }}"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm">{{ __('messages.back') }}</a>
                        </div>
                    </div>
                </div>
                <div class="border-b px-4 md:px-6 py-4">
                    <h3 class="text-lg font-semibold">{{ __('messages.product_information') }}</h3>
                </div>
                <div class="p-4 md:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                        <div>
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}"
                                    class="rounded-lg border w-full object-cover">
                            @else
                                <div class="h-64 flex items-center justify-center bg-gray-100 rounded-lg"><span
                                        class="text-gray-400">{{ __('messages.image') }}</span></div>
                            @endif
                        </div>
                        <div class="md:col-span-2 min-w-0">
                            <div class="grid grid-cols-2 gap-4 md:gap-6">
                                <div class="min-w-0">
                                    <p class="text-gray-500 text-sm">{{ __('messages.reference') }}</p>
                                    <p class="font-semibold break-words">{{ $product->reference }}</p>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-gray-500 text-sm">{{ __('messages.selling_price') }}</p>
                                    <p class="font-semibold text-green-600">{{ $product->price }} ¥</p>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-gray-500 text-sm">{{ __('messages.created_at') }}</p>
                                    <p>{{ $product->created_at->format('Y-m-d') }}</p>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-gray-500 text-sm">{{ __('messages.last_updated') }}</p>
                                    <p>{{ $product->updated_at->format('Y-m-d') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing -->
            <div class="bg-white rounded-lg shadow">
                <div class="border-b px-4 md:px-6 py-4">
                    <h3 class="text-lg font-semibold">{{ __('messages.pricing') }}</h3>
                </div>
                <div class="p-4 md:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                        <div class="bg-gray-50 rounded-lg p-5">
                            <p class="text-gray-500 text-sm">{{ __('messages.buy_price') }}</p>
                            <h2 class="text-2xl font-bold text-blue-600">{{ $lastBuyPrice ?? 'N/A' }} ¥</h2>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-5">
                            <p class="text-gray-500 text-sm">{{ __('messages.sell_price') }}</p>
                            <h2 class="text-2xl font-bold text-green-600">{{ $lastSellPrice ?? 'N/A' }} ¥</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Supplier Purchase History -->
            <div class="bg-white shadow rounded-lg overflow-hidden mt-6">
                <div
                    class="px-4 md:px-6 py-4 border-b flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <h3 class="text-lg font-semibold text-gray-800">{{ __('messages.supplier_purchase_history') }}</h3>
                    <a href="{{ route('products.purchases.create', $product->id) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm text-center">{{ __('messages.add_purchase') }}</a>
                </div>

                @if ($product->supplierPurchases->isEmpty())
                    <div class="px-4 md:px-6 py-6 text-center text-gray-500 text-sm">
                        {{ __('messages.no_purchases_found') }}</div>
                @else
                    <div class="lg:hidden divide-y divide-gray-200">
                        @foreach ($product->supplierPurchases as $purchase)
                            @php
                                $paid = $purchase->payments->sum('amount');
                                $remaining = $purchase->total_amount - $paid;
                            @endphp
                            <div class="p-4 space-y-2 text-sm">
                                <div class="flex justify-between items-start gap-2">
                                    <p class="font-semibold text-gray-900 break-words">{{ $purchase->supplier->name }}
                                    </p>
                                    @if ($purchase->payment_status == 'paid')
                                        <span
                                            class="px-2 py-1 rounded bg-green-100 text-green-700 text-xs shrink-0">{{ __('messages.paid') }}</span>
                                    @elseif($purchase->payment_status == 'partial')
                                        <span
                                            class="px-2 py-1 rounded bg-yellow-100 text-yellow-700 text-xs shrink-0">{{ __('messages.partial') }}</span>
                                    @else
                                        <span
                                            class="px-2 py-1 rounded bg-red-100 text-red-700 text-xs shrink-0">{{ __('messages.unpaid') }}</span>
                                    @endif
                                </div>
                                <div class="grid grid-cols-2 gap-2 text-gray-700">
                                    <p><span class="text-gray-500">{{ __('messages.qty') }}:</span>
                                        {{ $purchase->quantity }}</p>
                                    <p><span class="text-gray-500">{{ __('messages.date') }}:</span>
                                        {{ $purchase->purchase_date }}</p>
                                    <p><span class="text-gray-500">{{ __('messages.unit_price') }}:</span>
                                        {{ $purchase->unit_price }} {{ $purchase->currency }}</p>
                                    <p><span class="text-gray-500">{{ __('messages.total') }}:</span>
                                        {{ $purchase->total_amount }} {{ $purchase->currency }}</p>
                                    <p><span class="text-gray-500">{{ __('messages.paid') }}:</span>
                                        {{ number_format($paid, 2) }} {{ $purchase->currency }}</p>
                                    <p><span class="text-gray-500">{{ __('messages.remaining') }}:</span>
                                        {{ number_format($remaining, 2) }} {{ $purchase->currency }}</p>
                                    <p><span class="text-gray-500">{{ __('messages.payments') }}:</span>
                                        {{ $purchase->payments->count() }}</p>
                                </div>
                                <div class="pt-1">
                                    <a href="{{ route('supplier-payments.create', $purchase->id) }}"
                                        class="text-green-600 hover:text-green-800 text-sm">{{ __('messages.add_payment') }}</a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="hidden lg:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs uppercase">{{ __('messages.supplier') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs uppercase">{{ __('messages.quantity') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs uppercase">{{ __('messages.unit_price') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs uppercase">{{ __('messages.total') }}</th>
                                    <th class="px-6 py-3 text-left text-xs uppercase">{{ __('messages.status') }}</th>
                                    <th class="px-6 py-3 text-left text-xs uppercase">{{ __('messages.date') }}</th>
                                    <th class="px-6 py-3 text-left text-xs uppercase">{{ __('messages.paid') }}</th>
                                    <th class="px-6 py-3 text-left text-xs uppercase">{{ __('messages.remaining') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs uppercase">{{ __('messages.payments') }}
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs uppercase">{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($product->supplierPurchases as $purchase)
                                    @php
                                        $paid = $purchase->payments->sum('amount');
                                        $remaining = $purchase->total_amount - $paid;
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4">{{ $purchase->supplier->name }}</td>
                                        <td class="px-6 py-4">{{ $purchase->quantity }}</td>
                                        <td class="px-6 py-4">{{ $purchase->unit_price }} {{ $purchase->currency }}
                                        </td>
                                        <td class="px-6 py-4 font-semibold">{{ $purchase->total_amount }}
                                            {{ $purchase->currency }}</td>
                                        <td class="px-6 py-4">
                                            @if ($purchase->payment_status == 'paid')
                                                <span
                                                    class="px-2 py-1 rounded bg-green-100 text-green-700 text-xs">{{ __('messages.paid') }}</span>
                                            @elseif($purchase->payment_status == 'partial')
                                                <span
                                                    class="px-2 py-1 rounded bg-yellow-100 text-yellow-700 text-xs">{{ __('messages.partial') }}</span>
                                            @else
                                                <span
                                                    class="px-2 py-1 rounded bg-red-100 text-red-700 text-xs">{{ __('messages.unpaid') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">{{ $purchase->purchase_date }}</td>
                                        <td class="px-6 py-4">{{ number_format($paid, 2) }} {{ $purchase->currency }}
                                        </td>
                                        <td class="px-6 py-4">{{ number_format($remaining, 2) }}
                                            {{ $purchase->currency }}</td>
                                        <td class="px-6 py-4">{{ $purchase->payments->count() }}</td>
                                        <td class="px-6 py-4"><a
                                                href="{{ route('supplier-payments.create', $purchase->id) }}"
                                                class="text-green-600 hover:text-green-800 whitespace-nowrap">{{ __('messages.add_payment') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Suppliers -->
            <div class="bg-white rounded-lg shadow">
                <div class="border-b px-4 md:px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-semibold">{{ __('messages.suppliers') }}</h3>
                </div>
                @if ($product->supplierPurchases->groupBy('supplier_id')->isEmpty())
                    <div class="px-4 md:px-6 py-6 text-center text-gray-500 text-sm">
                        {{ __('messages.no_suppliers_found') }}</div>
                @else
                    <div class="lg:hidden divide-y divide-gray-200">
                        @foreach ($product->supplierPurchases->groupBy('supplier_id') as $supplierId => $supplierPurchases)
                            @php
                                $supplier = $supplierPurchases->first()->supplier;
                                $purchases = $product->supplierPurchases->where('supplier_id', $supplier->id);
                                $lastPurchase = $purchases->sortByDesc('purchase_date')->first();
                                $totalBought = $purchases->sum('total_amount');
                                $totalPaid = $purchases->flatMap->payments->sum('amount');
                                $debt = $totalBought - $totalPaid;
                            @endphp
                            <div class="p-4 space-y-2 text-sm">
                                <p class="font-semibold text-gray-900 break-words">{{ $supplier->name }}</p>
                                <div class="grid grid-cols-2 gap-2 text-gray-700">
                                    <p><span class="text-gray-500">{{ __('messages.last_buy') }}:</span>
                                        {{ $lastPurchase ? $lastPurchase->unit_price . ' ' . $lastPurchase->currency : '-' }}
                                    </p>
                                    <p><span class="text-gray-500">{{ __('messages.last_purchase') }}:</span>
                                        {{ $lastPurchase ? $lastPurchase->purchase_date : '-' }}</p>
                                    <p><span class="text-gray-500">{{ __('messages.purchases') }}:</span>
                                        {{ $purchases->count() }}</p>
                                    <p><span class="text-gray-500">{{ __('messages.total_bought') }}:</span>
                                        {{ number_format($totalBought, 2) }} RMB</p>
                                </div>
                                <p>
                                    <span class="text-gray-500">{{ __('messages.debt') }}:</span>
                                    @if ($debt > 0)
                                        <span class="text-red-600 font-semibold">{{ number_format($debt, 2) }}
                                            RMB</span>
                                    @else
                                        <span class="text-green-600 font-semibold">{{ __('messages.paid') }}</span>
                                    @endif
                                </p>
                                <div class="pt-1">
                                    <a href="{{ route('products.supplier-history', [$product->id, $supplier->id]) }}"
                                        class="text-blue-600 hover:text-blue-800 text-sm">{{ __('messages.view_history') }}</a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="hidden lg:block overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left">{{ __('messages.supplier') }}</th>
                                    <th class="px-6 py-3 text-left">{{ __('messages.buy_price') }}</th>
                                    <th class="px-6 py-3 text-left">{{ __('messages.last_purchase') }}</th>
                                    <th class="px-6 py-3 text-left">{{ __('messages.purchases') }}</th>
                                    <th class="px-6 py-3 text-left">{{ __('messages.total_bought') }}</th>
                                    <th class="px-6 py-3 text-left">{{ __('messages.debt') }}</th>
                                    <th class="px-6 py-3 text-left">{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($product->supplierPurchases->groupBy('supplier_id') as $supplierId => $supplierPurchases)
                                    @php
                                        $supplier = $supplierPurchases->first()->supplier;
                                        $purchases = $product->supplierPurchases->where('supplier_id', $supplier->id);
                                        $lastPurchase = $purchases->sortByDesc('purchase_date')->first();
                                        $totalBought = $purchases->sum('total_amount');
                                        $totalPaid = $purchases->flatMap->payments->sum('amount');
                                        $debt = $totalBought - $totalPaid;
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4">{{ $supplier->name }}</td>
                                        <td class="px-6 py-4">
                                            {{ $lastPurchase ? $lastPurchase->unit_price . ' ' . $lastPurchase->currency : '-' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $lastPurchase ? $lastPurchase->purchase_date : '-' }}</td>
                                        <td class="px-6 py-4">{{ $purchases->count() }}</td>
                                        <td class="px-6 py-4">{{ number_format($totalBought, 2) }} RMB</td>
                                        <td class="px-6 py-4">
                                            @if ($debt > 0)
                                                <span
                                                    class="text-red-600 font-semibold">{{ number_format($debt, 2) }}
                                                    RMB</span>
                                            @else
                                                <span
                                                    class="text-green-600 font-semibold">{{ __('messages.paid') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('products.supplier-history', [$product->id, $supplier->id]) }}"
                                                class="text-blue-600 hover:text-blue-800">{{ __('messages.view_history') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Clients -->
            <div class="bg-white rounded-lg shadow">
                <div class="border-b px-4 md:px-6 py-4">
                    <h3 class="text-lg font-semibold">{{ __('messages.clients') }}</h3>
                </div>
                @if ($product->proformaInvoices->isEmpty())
                    <div class="px-4 md:px-6 py-8 text-center text-gray-500 text-sm">
                        {{ __('messages.no_clients_linked') }}</div>
                @else
                    <div class="lg:hidden divide-y divide-gray-200">
                        @foreach ($product->proformaInvoices as $invoice)
                            <div class="p-4 space-y-2 text-sm">
                                <p class="font-semibold text-gray-900 break-words">{{ $invoice->client->name }}</p>
                                <div class="grid grid-cols-2 gap-2 text-gray-700">
                                    <p><span class="text-gray-500">{{ __('messages.sell_price') }}:</span>
                                        {{ $invoice->pivot->unit_price ?? '-' }}</p>
                                    <p><span class="text-gray-500">{{ __('messages.qty') }}:</span>
                                        {{ $invoice->pivot->ctn ?? '-' }}</p>
                                    <p><span class="text-gray-500">{{ __('messages.date') }}:</span>
                                        {{ $invoice->date }}</p>
                                </div>
                                <div class="pt-1">
                                    <a href="{{ route('proforma-invoices.show', $invoice->id) }}"
                                        class="text-blue-600 hover:text-blue-900 flex items-center gap-1 text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        {{ __('messages.view_invoice') }}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="hidden lg:block overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left">{{ __('messages.client') }}</th>
                                    <th class="px-6 py-3 text-left">{{ __('messages.sell_price') }}</th>
                                    <th class="px-6 py-3 text-left">{{ __('messages.quantity') }}</th>
                                    <th class="px-6 py-3 text-left">{{ __('messages.date') }}</th>
                                    <th class="px-6 py-3 text-left">{{ __('messages.invoice_no') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product->proformaInvoices as $invoice)
                                    <tr class="border-t">
                                        <td class="px-6 py-4">{{ $invoice->client->name }}</td>
                                        <td class="px-6 py-4">{{ $invoice->pivot->unit_price ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $invoice->pivot->ctn ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $invoice->date }}</td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('proforma-invoices.show', $invoice->id) }}"
                                                class="text-blue-600 hover:text-blue-900 flex items-center gap-1 whitespace-nowrap">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                {{ __('messages.view') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
