<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800">
            Add Supplier to {{ $product->reference }}
        </h2>

    </x-slot>


    <div class="py-6">

        <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow">


            <form action="{{ route('products.suppliers.store', $product->id) }}" method="POST">

                @csrf


                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">


                    <!-- Supplier -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Supplier
                        </label>

                        <select name="supplier_id"
                                class="w-full rounded-lg border-gray-300"
                                required>

                            <option value="">
                                Select Supplier
                            </option>

                            @foreach($suppliers as $supplier)

                                <option value="{{ $supplier->id }}">
                                    {{ $supplier->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <!-- Buying Price -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Buying Price
                        </label>

                        <input type="number"
                            step="0.01"
                            name="buying_price"
                            placeholder="0.00"
                            class="w-full rounded-lg border-gray-300"
                            required>

                    </div>


                    <!-- Payment Status -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Payment Status
                        </label>

                        <select name="payment_status"
                                class="w-full rounded-lg border-gray-300">

                            <option value="unpaid">
                                Unpaid
                            </option>

                            <option value="partial">
                                Partial
                            </option>

                            <option value="paid">
                                Paid
                            </option>

                        </select>

                    </div>


                    <!-- Payment Method -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Payment Method
                        </label>

                        <input type="text"
                            name="payment_method"
                            placeholder="Bank transfer / Cash"
                            class="w-full rounded-lg border-gray-300">

                    </div>


                    <!-- First Payment -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            First Payment Date
                        </label>

                        <input type="date"
                            name="date_first_payment"
                            class="w-full rounded-lg border-gray-300">

                    </div>


                    <!-- Remaining Payment -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Remaining Payment Date
                        </label>

                        <input type="date"
                            name="date_rest_payment"
                            class="w-full rounded-lg border-gray-300">

                    </div>


                    <!-- Discount -->
                    <div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Discount %
                        </label>

                        <input type="number"
                            step="0.01"
                            name="discount"
                            value="0"
                            class="w-full rounded-lg border-gray-300">

                    </div>


                </div>


                <div class="mt-8 flex justify-end">

                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">

                        Save Supplier

                    </button>

                </div>

                <div class="mt-8 flex justify-end">

                    <button type="submit"
                            class="bg-blue-700 text-black px-6 py-2 rounded-lg">

                        Save Supplier

                    </button>

                </div>


            </form>


        </div>

    </div>


</x-app-layout>