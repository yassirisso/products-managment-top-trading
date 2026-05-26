<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

            Bank Accounts

        </h2>

    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <!-- TOP -->
        <div class="flex justify-between items-center mb-6">

            <h1 class="text-2xl font-bold">

                Bank Accounts

            </h1>

            <a href="{{ route('bank-accounts.create') }}"
               class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">

                Add Bank

            </a>

        </div>

        <!-- SUCCESS -->
        @if(session('success'))

            <div class="bg-green-100 text-green-700 p-4 rounded mb-4">

                {{ session('success') }}

            </div>

        @endif

        <!-- TABLE -->
        <div class="bg-white shadow rounded-lg overflow-hidden">

            <table class="w-full">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="p-3 text-left">

                            Bank

                        </th>

                        <th class="p-3 text-left">

                            Account Number

                        </th>

                        <th class="p-3 text-left">

                            Swift

                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($bankAccounts as $bank)

                        <tr class="border-t">

                            <td class="p-3">

                                {{ $bank->bank_name }}

                            </td>

                            <td class="p-3">

                                {{ $bank->account_number }}

                            </td>

                            <td class="p-3">

                                {{ $bank->swift }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="3"
                                class="p-4 text-center text-gray-500">

                                No Bank Accounts Found

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</x-app-layout>