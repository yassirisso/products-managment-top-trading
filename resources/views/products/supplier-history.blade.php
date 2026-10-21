<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-lg md:text-xl text-gray-800">
            Supplier Purchase History
        </h2>

    </x-slot>


    <div class="py-6">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-lg p-4 md:p-6">

                <h3 class="text-base md:text-lg font-bold mb-2 break-words">

                    Product:
                    {{ $product->reference }}

                </h3>

                <h3 class="text-base md:text-lg font-bold mb-6 break-words">

                    Supplier:
                    {{ $supplier->name }}

                </h3>

                @if ($purchases->isEmpty())

                    <div class="text-center text-gray-500 text-sm py-6">
                        No purchases found.
                    </div>
                @else
                    <!-- MOBILE / TABLET : CARDS -->
                    <div class="lg:hidden space-y-4">

                        @foreach ($purchases as $purchase)
                            @php

                                $paid = $purchase->payments->sum('amount');

                                $remaining = $purchase->total_amount - $paid;

                            @endphp

                            <div class="border rounded-lg p-4 space-y-2 text-sm">

                                <!-- DATE -->
                                <div class="flex justify-between items-start gap-2">

                                    <p class="font-semibold text-gray-900">
                                        {{ $purchase->purchase_date }}
                                    </p>

                                    <span class="text-xs text-gray-500 shrink-0">
                                        {{ $purchase->currency }}
                                    </span>

                                </div>

                                <!-- NUMBERS -->
                                <div class="grid grid-cols-2 gap-2 text-gray-700">

                                    <p><span class="text-gray-500">Qty:</span> {{ $purchase->quantity }}</p>
                                    <p><span class="text-gray-500">Unit:</span> {{ $purchase->unit_price }}
                                        {{ $purchase->currency }}</p>
                                    <p><span class="text-gray-500">Total:</span> {{ $purchase->total_amount }}
                                        {{ $purchase->currency }}</p>
                                    <p><span class="text-gray-500">Paid:</span> {{ $paid }}
                                        {{ $purchase->currency }}</p>
                                    <p class="col-span-2"><span class="text-gray-500">Remaining:</span>
                                        {{ $remaining }} {{ $purchase->currency }}</p>

                                </div>

                                <!-- PAYMENTS -->
                                <div class="pt-2 border-t">

                                    <p class="text-gray-500 text-xs uppercase mb-2">Payments</p>

                                    @forelse($purchase->payments as $payment)
                                        <div class="border-b last:border-b-0 py-2">

                                            <div class="flex justify-between items-start gap-2">

                                                <strong class="break-words">
                                                    {{ $payment->amount }} {{ $payment->currency }}
                                                </strong>

                                                <span class="text-xs text-gray-500 shrink-0">
                                                    {{ $payment->payment_date }}
                                                </span>

                                            </div>

                                            @if ($payment->note)
                                                <div class="text-xs text-gray-600 mt-1 break-words">
                                                    {{ $payment->note }}
                                                </div>
                                            @endif

                                        </div>

                                    @empty

                                        <span class="text-gray-400 text-sm">
                                            No payments
                                        </span>
                                    @endforelse

                                </div>

                            </div>
                        @endforeach

                    </div>

                    <!-- DESKTOP : TABLE -->
                    <div class="hidden lg:block overflow-x-auto">

                        <table class="min-w-full">

                            <thead>

                                <tr class="border-b">

                                    <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">Date</th>
                                    <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">Quantity</th>
                                    <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">Unit Price</th>
                                    <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">Total</th>
                                    <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">Paid</th>
                                    <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">Remaining</th>
                                    <th class="px-4 py-3 text-left text-xs uppercase text-gray-500">Payments Details
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach ($purchases as $purchase)
                                    @php

                                        $paid = $purchase->payments->sum('amount');

                                        $remaining = $purchase->total_amount - $paid;

                                    @endphp

                                    <tr class="border-b align-top">

                                        <td class="px-4 py-3">
                                            {{ $purchase->purchase_date }}
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ $purchase->quantity }}
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ $purchase->unit_price }} {{ $purchase->currency }}
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ $purchase->total_amount }} {{ $purchase->currency }}
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ $paid }} {{ $purchase->currency }}
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ $remaining }} {{ $purchase->currency }}
                                        </td>

                                        <td class="px-4 py-3">

                                            @forelse($purchase->payments as $payment)
                                                <div class="border-b py-2 last:border-b-0">

                                                    <div>

                                                        <strong>
                                                            {{ $payment->amount }} {{ $payment->currency }}
                                                        </strong>

                                                    </div>

                                                    <div class="text-sm text-gray-600">

                                                        Date:
                                                        {{ $payment->payment_date }}

                                                    </div>

                                                    @if ($payment->note)
                                                        <div class="text-sm text-gray-600">

                                                            Note:
                                                            {{ $payment->note }}

                                                        </div>
                                                    @endif

                                                </div>

                                            @empty

                                                <span class="text-gray-400">
                                                    No payments
                                                </span>
                                            @endforelse

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
