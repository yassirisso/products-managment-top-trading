<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

            Add Bank Account

        </h2>

    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <div class="bg-white shadow rounded-lg p-6">

            <form action="{{ route('bank-accounts.store') }}"
                  method="POST">

                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- BENEFICIARY -->
                    <div>

                        <label class="block mb-1 font-medium">

                            Beneficiary Name

                        </label>

                        <input type="text"
                               name="beneficiary_name"
                               required
                               class="w-full border rounded-lg px-3 py-2">

                    </div>

                    <!-- ACCOUNT -->
                    <div>

                        <label class="block mb-1 font-medium">

                            Account Number

                        </label>

                        <input type="text"
                               name="account_number"
                               required
                               class="w-full border rounded-lg px-3 py-2">

                    </div>

                    <!-- SWIFT -->
                    <div>

                        <label class="block mb-1 font-medium">

                            Swift

                        </label>

                        <input type="text"
                               name="swift"
                               required
                               class="w-full border rounded-lg px-3 py-2">

                    </div>

                    <!-- BANK -->
                    <div>

                        <label class="block mb-1 font-medium">

                            Bank Name

                        </label>

                        <input type="text"
                               name="bank_name"
                               required
                               class="w-full border rounded-lg px-3 py-2">

                    </div>

                    <!-- ADDRESS -->
                    <div class="md:col-span-2">

                        <label class="block mb-1 font-medium">

                            Bank Address

                        </label>

                        <textarea
                            name="bank_address"
                            rows="4"
                            required
                            class="w-full border rounded-lg px-3 py-2"
                        ></textarea>

                    </div>

                </div>

                <!-- BUTTON -->
                <div class="flex justify-end mt-6">

                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-2 rounded">

                        Save Bank

                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>