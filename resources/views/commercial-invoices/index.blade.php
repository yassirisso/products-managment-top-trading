<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
            {{ __('messages.commercial_invoices_management') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <!-- TOP -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3 mb-6">
            <h1 class="text-xl md:text-2xl font-bold text-gray-800">
                {{ __('messages.commercial_invoices_management') }}
            </h1>

            <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto min-w-0">
                <form action="{{ route('commercial-invoices.index') }}" method="GET"
                    class="flex w-full sm:flex-1 sm:min-w-0 md:w-auto md:flex-none">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="{{ __('messages.search_commercial_invoice') }}"
                        class="flex-1 min-w-0 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 rounded-r-md text-sm">
                        {{ __('messages.search') }}
                    </button>
                </form>

                <a href="{{ route('commercial-invoices.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm text-center whitespace-nowrap">
                    {{ __('messages.create_commercial_invoice') }}
                </a>
            </div>
        </div>

        <!-- SUCCESS -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- EMPTY STATE -->
        @if ($commercialInvoices->isEmpty())
            <div class="bg-white shadow-md rounded-lg p-6 text-center text-gray-500 text-sm">
                {{ __('messages.no_commercial_invoices_found') }}
            </div>
        @else
            <!-- MOBILE / TABLET : CARDS -->
            <div class="lg:hidden space-y-4">
                @foreach ($commercialInvoices as $invoice)
                    <div class="bg-white shadow-md rounded-lg p-4">
                        <div class="flex justify-between items-start gap-2 mb-2">
                            <div class="min-w-0">
                                <a href="{{ route('commercial-invoices.show', $invoice) }}"
                                    class="text-blue-600 hover:text-blue-800 font-semibold text-base break-words">
                                    #{{ $invoice->invoice_no ?? $invoice->id }}
                                </a>
                                <p class="text-gray-700 text-sm break-words mt-1">{{ $invoice->client->name }}</p>
                            </div>
                            <span class="text-gray-400 text-xs shrink-0">ID: {{ $invoice->id }}</span>
                        </div>
                        <p class="text-sm text-gray-700 mb-3">
                            <span class="text-gray-500">{{ __('messages.date') }}:</span> {{ $invoice->date }}
                        </p>
                        <div class="flex justify-end items-center gap-3 pt-2 border-t">
                            <a href="{{ route('commercial-invoices.show', $invoice) }}"
                                class="text-blue-600 hover:text-blue-900" title="{{ __('messages.view') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            <a href="{{ route('commercial-invoices.download', $invoice) }}"
                                class="text-green-600 hover:text-green-900" title="{{ __('messages.download') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </a>
                            @can('edit commercial invoices')
                                <a href="{{ route('commercial-invoices.edit', $invoice) }}"
                                    class="text-indigo-600 hover:text-indigo-900" title="{{ __('messages.edit') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </a>
                            @endcan
                        </div>
                    </div>
                @endforeach
                <div class="pt-2">{{ $commercialInvoices->withQueryString()->links() }}</div>
            </div>

            <!-- DESKTOP : TABLE -->
            <div class="hidden lg:block bg-white shadow-md rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.client') }}</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.invoice_no') }}</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.date') }}</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($commercialInvoices as $invoice)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm">{{ $invoice->id }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $invoice->client->name }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $invoice->invoice_no }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $invoice->date }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center items-center space-x-3">
                                            <a href="{{ route('commercial-invoices.show', $invoice) }}"
                                                class="text-blue-600 hover:text-blue-900"
                                                title="{{ __('messages.view') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('commercial-invoices.download', $invoice) }}"
                                                class="text-green-600 hover:text-green-900"
                                                title="{{ __('messages.download') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                            </a>
                                            @can('edit commercial invoices')
                                                <a href="{{ route('commercial-invoices.edit', $invoice) }}"
                                                    class="text-indigo-600 hover:text-indigo-900"
                                                    title="{{ __('messages.edit') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path
                                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                    </svg>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6 px-6 pb-6">{{ $commercialInvoices->withQueryString()->links() }}</div>
            </div>
        @endif
    </div>
</x-app-layout>
