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
                    <h2 class="font-bold text-base md:text-lg mb-3">{{ __('messages.packing_lists') }}</h2>
                    @if ($receivingNote->packingList)
                        <p class="mb-2">
                            <a href="{{ route('packing-lists.show', $receivingNote->packingList) }}"
                                class="text-blue-600 hover:underline">
                                #{{ $receivingNote->packingList->id }}
                            </a>
                        </p>
                        <p class="mb-2"><strong>{{ __('messages.client') }}:</strong>
                            {{ $receivingNote->packingList->client?->name }}</p>
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

            <!-- MOBILE / TABLET : CARDS (grouped by supplier) -->
            <div class="lg:hidden space-y-3">
                @foreach ($receivingNote->products->groupBy(fn($p) => $p->suppliers->first()?->id) as $supplierId => $products)
                    @php
                        $supplier = $products->first()->suppliers->first();
                        $sumCtn = $products->sum(fn($p) => $p->pivot->ctn);
                        $sumQty = $products->sum(fn($p) => $p->pivot->ctn * ($p->pcs_cts ?? 0));
                        $sumCbm = $products->sum(fn($p) => $p->pivot->ctn * ($p->unit_cbm ?? 0));
                        $sumGw = $products->sum(fn($p) => $p->pivot->ctn * ($p->unit_gw ?? 0));
                        $sumNw = $products->sum(fn($p) => $p->pivot->ctn * ($p->unit_nw ?? 0));
                    @endphp
                    <div class="border rounded-lg p-3 text-sm">
                        <p class="font-semibold text-gray-900 break-words mb-2">
                            @if ($supplier)
                                <a href="{{ route('receiving-notes.supplier-details', [$receivingNote, $supplier]) }}"
                                    class="text-blue-600 hover:underline">
                                    {{ $supplier->name }}
                                </a>
                            @else
                                —
                            @endif
                        </p>
                        <div class="grid grid-cols-2 gap-2 text-gray-700">
                            <p><span class="text-gray-500">{{ __('messages.product') }}:</span>
                                {{ $products->count() }}</p>
                            <p><span class="text-gray-500">{{ __('messages.ctn') }}:</span> {{ $sumCtn }}</p>
                            <p><span class="text-gray-500">{{ __('messages.qty') }}:</span> {{ $sumQty }}</p>
                            <p><span class="text-gray-500">CBM:</span> {{ number_format($sumCbm, 3) }}</p>
                            <p><span class="text-gray-500">GW:</span> {{ number_format($sumGw, 2) }}</p>
                            <p><span class="text-gray-500">NW:</span> {{ number_format($sumNw, 2) }}</p>
                        </div>
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

            <!-- DESKTOP : TABLE (grouped by supplier) -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full border border-gray-400">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border p-2">{{ __('messages.supplier') }}</th>
                            <th class="border p-2">{{ __('messages.product') }} Count</th>
                            <th class="border p-2">{{ __('messages.ctn') }}</th>
                            <th class="border p-2">{{ __('messages.qty') }}</th>
                            <th class="border p-2">{{ __('messages.total_cbm') }}</th>
                            <th class="border p-2">{{ __('messages.total_gw') }}</th>
                            <th class="border p-2">{{ __('messages.total_nw') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($receivingNote->products->groupBy(fn($p) => $p->suppliers->first()?->id) as $supplierId => $products)
                            @php
                                $supplier = $products->first()->suppliers->first();
                                $sumCtn = $products->sum(fn($p) => $p->pivot->ctn);
                                $sumQty = $products->sum(fn($p) => $p->pivot->ctn * ($p->pcs_cts ?? 0));
                                $sumCbm = $products->sum(fn($p) => $p->pivot->ctn * ($p->unit_cbm ?? 0));
                                $sumGw = $products->sum(fn($p) => $p->pivot->ctn * ($p->unit_gw ?? 0));
                                $sumNw = $products->sum(fn($p) => $p->pivot->ctn * ($p->unit_nw ?? 0));
                            @endphp
                            <tr>
                                <td class="border p-2">
                                    @if ($supplier)
                                        <a href="{{ route('receiving-notes.supplier-details', [$receivingNote, $supplier]) }}"
                                            class="text-blue-600 hover:underline">
                                            {{ $supplier->name }}
                                        </a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="border p-2 text-center">{{ $products->count() }}</td>
                                <td class="border p-2 text-center">{{ $sumCtn }}</td>
                                <td class="border p-2 text-center">{{ $sumQty }}</td>
                                <td class="border p-2 text-center">{{ number_format($sumCbm, 3) }}</td>
                                <td class="border p-2 text-center">{{ number_format($sumGw, 2) }}</td>
                                <td class="border p-2 text-center">{{ number_format($sumNw, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-100 font-bold">
                        <tr>
                            <td colspan="2" class="border p-2 text-right">{{ __('messages.total') }}</td>
                            <td class="border p-2 text-center">{{ $totalCtn }}</td>
                            <td class="border p-2 text-center">{{ $totalQty }}</td>
                            <td class="border p-2 text-center">{{ number_format($totalCbm, 3) }}</td>
                            <td class="border p-2 text-center">{{ number_format($totalGw, 2) }}</td>
                            <td class="border p-2 text-center">{{ number_format($totalNw, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @role('Admin')
                @php
                    $totalPaid = $receivingNote->payments->sum('amount');
                @endphp
                <div class="mt-8 bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-3">
                        <h3 class="text-lg font-semibold text-gray-800">{{ __('messages.supplier_payments') }}</h3>
                        @can('create supplier payments')
                            <a href="{{ route('supplier-receiving-payments.create', ['receiving_note_id' => $receivingNote->id]) }}"
                                class="text-blue-600 hover:underline text-sm">
                                + {{ __('messages.new_supplier_payment') }}
                            </a>
                        @endcan
                    </div>

                    <div class="flex flex-wrap gap-6 text-sm mb-4">
                        <div><span class="text-gray-500">{{ __('messages.total') }} {{ __('messages.paid') }}:</span>
                            <strong class="text-green-600">{{ number_format($totalPaid, 2) }}</strong>
                        </div>
                    </div>

                    @if ($receivingNote->payments->isEmpty())
                        <p class="text-gray-500 text-sm">{{ __('messages.no_supplier_payments_found') }}</p>
                    @else
                        <table class="min-w-full text-sm border border-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left">{{ __('messages.date') }}</th>
                                    <th class="px-3 py-2 text-left">{{ __('messages.supplier') }}</th>
                                    <th class="px-3 py-2 text-right">{{ __('messages.amount') }}</th>
                                    <th class="px-3 py-2 text-left">{{ __('messages.note') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($receivingNote->payments as $payment)
                                    <tr>
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            {{ $payment->payment_date->format('Y-m-d') }}</td>
                                        <td class="px-3 py-2 whitespace-nowrap">{{ $payment->supplier?->name ?? '—' }}</td>
                                        <td class="px-3 py-2 text-right font-medium">
                                            {{ number_format($payment->amount, 2) }} {{ $payment->currency }}</td>
                                        <td class="px-3 py-2">{{ $payment->note ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            @endrole
        </div>
    </div>
</x-app-layout>
