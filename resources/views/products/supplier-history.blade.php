<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800">
            Supplier Purchase History
        </h2>

    </x-slot>


    <div class="py-6">

        <div class="max-w-7xl mx-auto bg-white shadow rounded-lg p-6">


            <h3 class="text-lg font-bold mb-4">

                Product:
                {{ $product->reference }}

            </h3>


            <h3 class="text-lg font-bold mb-6">

                Supplier:
                {{ $supplier->name }}

            </h3>



            <table class="min-w-full">

                <thead>

                    <tr>

                        <th>Date</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Remaining</th>
                        <th>Payments Details</th>

                    </tr>

                </thead>


                <tbody>


                    @foreach ($purchases as $purchase)
                        @php

                            $paid = $purchase->payments->sum('amount');

                            $remaining = $purchase->total_amount - $paid;

                        @endphp


                        <tr>


                            <td>
                                {{ $purchase->purchase_date }}
                            </td>


                            <td>
                                {{ $purchase->quantity }}
                            </td>


                            <td>
                                {{ $purchase->unit_price }}
                                {{ $purchase->currency }}
                            </td>


                            <td>
                                {{ $purchase->total_amount }}
                                {{ $purchase->currency }}
                            </td>


                            <td>
                                {{ $paid }}
                                {{ $purchase->currency }}
                            </td>


                            <td>
                                {{ $remaining }}
                                {{ $purchase->currency }}
                            </td>

                            <td>

                                @forelse($purchase->payments as $payment)
                                    <div class="border-b py-2">

                                        <div>
                                            <strong>
                                                {{ $payment->amount }}
                                                {{ $payment->currency }}
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

    </div>


</x-app-layout>
