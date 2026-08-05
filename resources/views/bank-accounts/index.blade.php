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

            <div class="flex flex-wrap gap-2 items-center">

                <!-- Search Form -->
                <form action="{{ route('bank-accounts.index') }}" method="GET" class="flex">

                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search bank account..."
                        class="px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500">

                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 rounded-r-md">

                        Search

                    </button>

                </form>


                <!-- Add Bank Account Button -->
                <a href="{{ route('bank-accounts.create') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                        fill="currentColor">

                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />

                    </svg>

                    Add Bank Account

                </a>


            </div>

        </div>

        <!-- SUCCESS -->
        @if (session('success'))
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

                            <td colspan="3" class="p-4 text-center text-gray-500">

                                No Bank Accounts Found

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

            <div class="mt-6">
                {{ $bankAccounts->links() }}
            </div>

        </div>

    </div>

</x-app-layout>
