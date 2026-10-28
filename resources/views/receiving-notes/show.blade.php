<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
            {{ __('messages.receiving_note_details') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
            <a href="{{ route('receiving-notes.download', $receivingNote) }}"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-center text-sm w-full sm:w-auto">
                {{ __('messages.download_excel') }}
            </a>
            <a href="{{ route('receiving-notes.index') }}"
                class="text-gray-600 hover:text-gray-800 text-sm text-center sm:text-right">
                &larr; {{ __('messages.back_to_receiving_notes') }}
            </a>
        </div>

        <!-- HEADER -->
        <div class="bg-white shadow-lg rounded-lg p-4 md:p-8">

            <div class="text-center mb-6 md:mb-8">
                <h1 class="text-2xl md:text-3xl font-bold uppercase">
                    {{ __('messages.receiving_notes') }}
                </h1>
            </div>

            <!-- INFO -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 mb-8">
                <div class="min-w-0 text-sm md:text-base">
                    <h2 class="font-bold text-base md:text-lg mb-3">{{ __('messages.container_info') }}</h2>
                    <p class="mb-2 break-words"><strong>{{ __('messages.container_no') }}:</strong>
                        {{ $receivingNote->container_no ?? '—' }}</p>
                    <p class="mb-2 break-words"><strong>{{ __('messages.seal_no') }}:</strong>
                        {{ $receivingNote->seal_no ?? '—' }}</p>
                    <p class="mb-2"><strong>{{ __('messages.date') }}:</strong>
                        {{ $receivingNote->date ? $receivingNote->date->format('Y-m-d') : '—' }}</p>
                </div>

                <div class="min-w-0 text-sm md:text-base">
                    <h2 class="font-bold text-base md:text-lg mb-3">{{ __('messages.proforma_invoices') }}</h2>
                    @if ($receivingNote->proformaInvoice)
                        <p class="mb-2">
                            <a href="{{ route('proforma-invoices.show', $receivingNote->proformaInvoice) }}"
                                class="text-blue-600 hover:underline">
                                #{{ $receivingNote->proformaInvoice->id }}
                            </a>
                        </p>
                        <p class="mb-2"><strong>{{ __('messages.client') }}:</strong>
                            {{ $receivingNote->proformaInvoice->client?->name }}</p>
                    @else
                        <p class="text-gray-500">—</p>
                    @endif
                </div>
            </div>

            @if ($receivingNote->note)
                <div class="mb-6 bg-gray-50 border border-gray-200 rounded p-3 text-sm">
                    <strong>{{ __('messages.note') }}:</strong> {{ $receivingNote->note }}
                </div>
            @endif

            @php
                $totalCtn = $receivingNote->products->sum(fn($p) => $p->pivot->ctn);
                $totalQty = $receivingNote->products->sum(fn($p) => $p->pivot->ctn * ($p->pcs_cts ?? 0));
                $totalCbm = $receivingNote->products->sum(fn($p) => $p->pivot->ctn * ($p->unit_cbm ?? 0));
                $totalGw = $receivingNote->products->sum(fn($p) => $p->pivot->ctn * ($p->unit_gw ?? 0));
                $totalNw = $receivingNote->products->sum(fn($p) => $p->pivot->ctn * ($p->unit_nw ?? 0));
            @endphp

            <!-- MOBILE / TABLET : CARDS -->
            <div class="lg:hidden space-y-3">
                @foreach ($receivingNote->products as $product)
                    @php
                        $qty = $product->pivot->ctn * ($product->pcs_cts ?? 0);
                        $cbm = $product->pivot->ctn * ($product->unit_cbm ?? 0);
                        $gw = $product->pivot->ctn * ($product->unit_gw ?? 0);
                        $nw = $product->pivot->ctn * ($product->unit_nw ?? 0);
                    @endphp
                    <div class="border rounded-lg p-3 text-sm">
                        <p class="font-semibold text-gray-900 break-words">{{ $product->reference }}</p>
                        <p class="text-gray-500 text-xs mb-2">{{ $product->suppliers->first()?->name ?? '—' }}</p>
                        <div class="grid grid-cols-2 gap-2 text-gray-700">
                            <p><span class="text-gray-500">{{ __('messages.ctn') }}:</span> {{ $product->pivot->ctn }}
                            </p>
                            <p><span class="text-gray-500">{{ __('messages.pcs_cts') }}:</span>
                                {{ $product->pcs_cts }}</p>
                            <p><span class="text-gray-500">{{ __('messages.qty') }}:</span> {{ $qty }}</p>
                            <p><span class="text-gray-500">CBM:</span> {{ number_format($cbm, 3) }}</p>
                            <p><span class="text-gray-500">GW:</span> {{ number_format($gw, 2) }}</p>
                            <p><span class="text-gray-500">NW:</span> {{ number_format($nw, 2) }}</p>
                        </div>
                        @if ($product->pivot->note)
                            <p class="mt-2 text-xs text-gray-600"><strong>{{ __('messages.note') }}:</strong>
                                {{ $product->pivot->note }}</p>
                        @endif
                    </div>
                @endforeach

                <!-- TOTALS CARD -->
                <div class="bg-gray-100 rounded-lg p-3 text-sm font-semibold text-gray-800">
                    <p class="mb-2">{{ __('messages.total') }}</p>
                    <div class="grid grid-cols-2 gap-2">
                        <p><span class="text-gray-500 font-normal">CTN:</span> {{ $totalCtn }}</p>
                        <p><span class="text-gray-500 font-normal">QTY:</span> {{ $totalQty }}</p>
                        <p><span class="text-gray-500 font-normal">CBM:</span> {{ number_format($totalCbm, 3) }}</p>
                        <p><span class="text-gray-500 font-normal">GW:</span> {{ number_format($totalGw, 2) }}</p>
                        <p><span class="text-gray-500 font-normal">NW:</span> {{ number_format($totalNw, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- DESKTOP : TABLE -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full border border-gray-400">
                    <thead class="bg-gray-200">
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($receivingNote->products as $product)
                            @php
                                $qty = $product->pivot->ctn * ($product->pcs_cts ?? 0);
                                $cbm = $product->pivot->ctn * ($product->unit_cbm ?? 0);
                                $gw = $product->pivot->ctn * ($product->unit_gw ?? 0);
                                $nw = $product->pivot->ctn * ($product->unit_nw ?? 0);
                            @endphp
                            <tr>
                                <td class="border p-2 text-center">{{ $product->reference }}</td>
                                <td class="border p-2">{{ $product->suppliers->first()?->name ?? '—' }}</td>
                                <td class="border p-2 text-center">{{ $product->pivot->ctn }}</td>
                                <td class="border p-2 text-center">{{ $product->pcs_cts }}</td>
                                <td class="border p-2 text-center">{{ $qty }}</td>
                                <td class="border p-2 text-center">{{ number_format($cbm, 3) }}</td>
                                <td class="border p-2 text-center">{{ number_format($gw, 2) }}</td>
                                <td class="border p-2 text-center">{{ number_format($nw, 2) }}</td>
                                <td class="border p-2">{{ $product->pivot->note ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-100 font-bold">
                        <tr>
                            <td colspan="2" class="border p-2 text-right">{{ __('messages.total') }}</td>
                            <td class="border p-2 text-center">{{ $totalCtn }}</td>
                            <td class="border p-2"></td>
                            <td class="border p-2 text-center">{{ $totalQty }}</td>
                            <td class="border p-2 text-center">{{ number_format($totalCbm, 3) }}</td>
                            <td class="border p-2 text-center">{{ number_format($totalGw, 2) }}</td>
                            <td class="border p-2 text-center">{{ number_format($totalNw, 2) }}</td>
                            <td class="border p-2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
