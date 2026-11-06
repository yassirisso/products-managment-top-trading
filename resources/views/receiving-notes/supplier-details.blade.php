<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
            {{ $supplier->name }} — {{ __('messages.receiving_note_details') }} #{{ $receivingNote->id }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
            <a href="{{ route('receiving-notes.show', $receivingNote) }}" class="text-blue-600 hover:underline text-sm">
                &larr; {{ __('messages.back_to_receiving_notes') }}
            </a>
            <a href="{{ route('receiving-notes.supplier-download', [$receivingNote, $supplier]) }}"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm text-center whitespace-nowrap">
                {{ __('messages.download_excel') }}
            </a>
        </div>

        <div class="bg-white shadow-lg rounded-lg p-4 md:p-8">

            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">{{ $supplier->name }}</h1>
                <p class="text-gray-600 text-sm">
                    {{ __('messages.receiving_notes') }} #{{ $receivingNote->id }}
                    — {{ __('messages.container_no') }}: {{ $receivingNote->container_no ?? '—' }}
                    — {{ __('messages.date') }}:
                    {{ $receivingNote->date ? $receivingNote->date->format('Y-m-d') : '—' }}
                </p>
            </div>

            <!-- Mobile cards -->
            <div class="lg:hidden space-y-3">
                @foreach ($products as $product)
                    @php
                        $qty = $product->pivot->ctn * ($product->pcs_cts ?? 0);
                        $cbm = $product->pivot->ctn * ($product->unit_cbm ?? 0);
                        $gw = $product->pivot->ctn * ($product->unit_gw ?? 0);
                        $nw = $product->pivot->ctn * ($product->unit_nw ?? 0);
                    @endphp
                    <div class="border rounded-lg p-3 text-sm">
                        <p class="font-semibold text-gray-900 break-words">{{ $product->reference }}</p>
                        <div class="grid grid-cols-2 gap-2 text-gray-700 mt-2">
                            <p><span class="text-gray-500">{{ __('messages.ctn') }}:</span> {{ $product->pivot->ctn }}
                            </p>
                            <p><span class="text-gray-500">{{ __('messages.unit_cbm') }}:</span>
                                {{ number_format($product->unit_cbm ?? 0, 3) }}</p>
                            <p><span class="text-gray-500">{{ __('messages.unit_gw') }}:</span>
                                {{ number_format($product->unit_gw ?? 0, 2) }}</p>
                            <p><span class="text-gray-500">{{ __('messages.pcs_cts') }}:</span>
                                {{ $product->pcs_cts }}</p>
                            <p><span class="text-gray-500">{{ __('messages.qty') }}:</span> {{ $qty }}</p>
                            <p><span class="text-gray-500">{{ __('messages.total_cbm') }}:</span>
                                {{ number_format($cbm, 3) }}</p>
                            <p><span class="text-gray-500">{{ __('messages.total_gw') }}:</span>
                                {{ number_format($gw, 2) }}</p>
                            <p><span class="text-gray-500">{{ __('messages.total_nw') }}:</span>
                                {{ number_format($nw, 2) }}</p>
                        </div>
                        @if ($product->pivot->note)
                            <p class="mt-2 text-xs text-gray-600"><strong>{{ __('messages.note') }}:</strong>
                                {{ $product->pivot->note }}</p>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Desktop table -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full border border-gray-400">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border p-2">{{ __('messages.product') }}</th>
                            <th class="border p-2">{{ __('messages.ctn') }}</th>
                            <th class="border p-2">{{ __('messages.unit_cbm') }}</th>
                            <th class="border p-2">{{ __('messages.pcs_cts') }}</th>
                            <th class="border p-2">{{ __('messages.qty') }}</th>
                            <th class="border p-2">{{ __('messages.unit_gw') }}</th>
                            <th class="border p-2">{{ __('messages.total_cbm') }}</th>
                            <th class="border p-2">{{ __('messages.total_gw') }}</th>
                            <th class="border p-2">{{ __('messages.total_nw') }}</th>
                            <th class="border p-2">{{ __('messages.note') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            @php
                                $qty = $product->pivot->ctn * ($product->pcs_cts ?? 0);
                                $cbm = $product->pivot->ctn * ($product->unit_cbm ?? 0);
                                $gw = $product->pivot->ctn * ($product->unit_gw ?? 0);
                                $nw = $product->pivot->ctn * ($product->unit_nw ?? 0);
                            @endphp
                            <tr>
                                <td class="border p-2 text-center">{{ $product->reference }}</td>
                                <td class="border p-2 text-center">{{ $product->pivot->ctn }}</td>
                                <td class="border p-2 text-center">{{ number_format($product->unit_cbm ?? 0, 3) }}</td>
                                <td class="border p-2 text-center">{{ $product->pcs_cts }}</td>
                                <td class="border p-2 text-center">{{ $qty }}</td>
                                <td class="border p-2 text-center">{{ number_format($product->unit_gw ?? 0, 2) }}</td>
                                <td class="border p-2 text-center">{{ number_format($cbm, 3) }}</td>
                                <td class="border p-2 text-center">{{ number_format($gw, 2) }}</td>
                                <td class="border p-2 text-center">{{ number_format($nw, 2) }}</td>
                                <td class="border p-2">{{ $product->pivot->note ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-100 font-bold">
                        @php
                            $supplierTotalCtn = $products->sum(fn($p) => $p->pivot->ctn);
                            $supplierTotalQty = $products->sum(fn($p) => $p->pivot->ctn * ($p->pcs_cts ?? 0));
                            $supplierTotalCbm = $products->sum(fn($p) => $p->pivot->ctn * ($p->unit_cbm ?? 0));
                            $supplierTotalGw = $products->sum(fn($p) => $p->pivot->ctn * ($p->unit_gw ?? 0));
                            $supplierTotalNw = $products->sum(fn($p) => $p->pivot->ctn * ($p->unit_nw ?? 0));
                        @endphp
                        <tr>
                            <td colspan="1" class="border p-2 text-right">{{ __('messages.total') }}</td>
                            <td class="border p-2 text-center">{{ $supplierTotalCtn }}</td>
                            <td class="border p-2"></td>
                            <td class="border p-2"></td>
                            <td class="border p-2 text-center">{{ $supplierTotalQty }}</td>
                            <td class="border p-2"></td>
                            <td class="border p-2 text-center">{{ number_format($supplierTotalCbm, 3) }}</td>
                            <td class="border p-2 text-center">{{ number_format($supplierTotalGw, 2) }}</td>
                            <td class="border p-2 text-center">{{ number_format($supplierTotalNw, 2) }}</td>
                            <td class="border p-2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
