<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
            {{ __('messages.bank_accounts_management') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <!-- TOP -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3 mb-6">
            <h1 class="text-xl md:text-2xl font-bold">
                {{ __('messages.bank_accounts_management') }}
            </h1>

            <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto min-w-0">
                <!-- Search Form -->
                <form action="{{ route('bank-accounts.index') }}" method="GET"
                    class="flex w-full sm:flex-1 sm:min-w-0 md:w-auto md:flex-none">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="{{ __('messages.search_bank_account') }}"
                        class="flex-1 min-w-0 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 rounded-r-md text-sm">
                        {{ __('messages.search') }}
                    </button>
                </form>

                <!-- Add Bank Account Button -->
                <a href="{{ route('bank-accounts.create') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center justify-center text-sm whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ __('messages.add_bank_account') }}
                </a>
            </div>
        </div>

        <!-- SUCCESS -->
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- EMPTY STATE -->
        @if ($bankAccounts->isEmpty())
            <div class="bg-white shadow rounded-lg p-6 text-center text-gray-500 text-sm">
                {{ __('messages.no_bank_accounts_found') }}
            </div>
        @else
            <!-- MOBILE / TABLET : CARDS -->
            <div class="lg:hidden space-y-4">
                @foreach ($bankAccounts as $bank)
                    <div class="bg-white shadow rounded-lg p-4 text-sm">
                        <p class="font-semibold text-gray-900 break-words mb-2">{{ $bank->bank_name }}</p>
                        <div class="space-y-1 text-gray-700">
                            <p class="break-words"><span
                                    class="text-gray-500">{{ __('messages.account_number') }}:</span>
                                {{ $bank->account_number }}</p>
                            <p class="break-words"><span class="text-gray-500">{{ __('messages.swift') }}:</span>
                                {{ $bank->swift }}</p>
                        </div>
                        <a href="{{ route('bank-accounts.edit', $bank) }}"
                            class="text-indigo-600 hover:text-indigo-900 text-sm mt-3 block">
                            {{ __('messages.edit') }}
                        </a>
                    </div>
                @endforeach
                <div class="pt-2">{{ $bankAccounts->withQueryString()->links() }}</div>
            </div>

            <!-- DESKTOP : TABLE -->
            <div class="hidden lg:block bg-white shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.bank_name') }}</th>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.account_number') }}</th>
                                <th class="p-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.swift') }}</th>
                                <th class="p-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bankAccounts as $bank)
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="p-3 text-sm">{{ $bank->bank_name }}</td>
                                    <td class="p-3 text-sm">{{ $bank->account_number }}</td>
                                    <td class="p-3 text-sm">{{ $bank->swift }}</td>
                                    <td class="p-3 text-right text-sm">
                                        <a href="{{ route('bank-accounts.edit', $bank) }}"
                                            class="text-indigo-600 hover:text-indigo-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path
                                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6 px-4 pb-6">{{ $bankAccounts->withQueryString()->links() }}</div>
            </div>
        @endif
    </div>
</x-app-layout>
