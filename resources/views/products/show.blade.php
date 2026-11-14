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
