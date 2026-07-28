<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800">

            Add Payment

        </h2>

    </x-slot>


    <div class="py-6">

        <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow">


            <div class="mb-6">

                <h3 class="text-lg font-semibold">

                    Purchase Information

                </h3>


                <p>
                    Supplier:
                    {{ $purchase->supplier->name }}
                </p>


                <p>
                    Total:
                    {{ $purchase->total_amount }}
                    {{ $purchase->currency }}
                </p>

            </div>



            <form action="{{ route('supplier-payments.store', $purchase->id) }}"
                  method="POST">

                @csrf


                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">


                    <div>

                        <label class="block text-sm font-medium">
                            Amount
                        </label>

                        <input type="number"
                               step="0.01"
                               name="amount"
                               class="w-full rounded-lg border-gray-300"
                               required>

                    </div>



                    <div>

                        <label class="block text-sm font-medium">
                            Currency
                        </label>


                        <select name="currency"
                                class="w-full rounded-lg border-gray-300">


                            <option value="RMB">
                                RMB
                            </option>


                            <option value="USD">
                                USD
                            </option>


                        </select>

                    </div>



                    <div>

                        <label class="block text-sm font-medium">
                            Payment Date
                        </label>


                        <input type="date"
                               name="payment_date"
                               value="{{ date('Y-m-d') }}"
                               class="w-full rounded-lg border-gray-300"
                               required>

                    </div>


                </div>


                <div class="mt-6">

                    <label class="block text-sm font-medium">
                        Note
                    </label>


                    <textarea name="note"
                              class="w-full rounded-lg border-gray-300"></textarea>

                </div>



                <div class="mt-6 flex justify-end">

                    <button class="bg-green-600 text-white px-6 py-2 rounded-lg">

                        Save Payment

                    </button>

                </div>


            </form>


        </div>

    </div>


</x-app-layout>