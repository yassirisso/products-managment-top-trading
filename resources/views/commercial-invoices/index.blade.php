<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">

            Commercial Invoices

        </h2>

    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <!-- TOP -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3 mb-6">

            <h1 class="text-xl md:text-2xl font-bold text-gray-800">

                Commercial Invoices

            </h1>

            <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto min-w-0">

                <form action="{{ route('commercial-invoices.index') }}" method="GET"
                    class="flex w-full sm:flex-1 sm:min-w-0 md:w-auto md:flex-none">

                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search invoice or client..."
                        class="flex-1 min-w-0 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">

                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 rounded-r-md text-sm">

                        Search

                    </button>

                </form>

                <a href="{{ route('commercial-invoices.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm text-center whitespace-nowrap">

                    Create Commercial Invoice

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

                No Commercial Invoices Found

            </div>
        @else
            <!-- MOBILE / TABLET : CARDS -->
            <div class="lg:hidden space-y-4">

                @foreach ($commercialInvoices as $invoice)
                    <div class="bg-white shadow-md rounded-lg p-4">

                        <!-- TOP ROW -->
                        <div class="flex justify-between items-start gap-2 mb-2">

                            <div class="min-w-0">

                                <a href="{{ route('commercial-invoices.show', $invoice) }}"
                                    class="text-blue-600 hover:text-blue-800 font-semibold text-base break-words">

                                    #{{ $invoice->invoice_no ?? $invoice->id }}

                                </a>

                                <p class="text-gray-700 text-sm break-words mt-1">
                                    {{ $invoice->client->name }}
                                </p>

                            </div>

                            <span class="text-gray-400 text-xs shrink-0">

                                ID: {{ $invoice->id }}

                            </span>

                        </div>

                        <!-- DATE -->
                        <p class="text-sm text-gray-700 mb-3">

                            <span class="text-gray-500">Date:</span> {{ $invoice->date }}

                        </p>

                        <!-- ACTIONS -->
                        <div class="flex gap-2">

                            <a href="{{ route('commercial-invoices.show', $invoice) }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm flex-1 text-center">

                                View

                            </a>

                            <a href="{{ route('commercial-invoices.download', $invoice) }}"
                                class="bg-green-500 hover:bg-green-700 text-white px-3 py-1 rounded text-sm flex-1 text-center">

                                Excel

                            </a>

                        </div>

                    </div>
                @endforeach

                <div class="pt-2">

                    {{ $commercialInvoices->withQueryString()->links() }}

                </div>

            </div>

            <!-- DESKTOP : TABLE -->
            <div class="hidden lg:block bg-white shadow-md rounded-lg overflow-hidden">

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-100">

                            <tr>

                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID
                                </th>

                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Client
                                </th>

                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Invoice No
                                </th>

                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>

                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-gray-200">

                            @foreach ($commercialInvoices as $invoice)
                                <tr class="hover:bg-gray-50">

                                    <td class="px-6 py-4 text-sm">
                                        {{ $invoice->id }}
                                    </td>

                                    <td class="px-6 py-4 text-sm">
                                        {{ $invoice->client->name }}
                                    </td>

                                    <td class="px-6 py-4 text-sm">
                                        {{ $invoice->invoice_no }}
                                    </td>

                                    <td class="px-6 py-4 text-sm">
                                        {{ $invoice->date }}
                                    </td>

                                    <td class="px-6 py-4 text-center">

                                        <div class="flex justify-center space-x-2">

                                            <a href="{{ route('commercial-invoices.show', $invoice) }}"
                                                class="bg-gray-500 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm">

                                                View

                                            </a>

                                            <a href="{{ route('commercial-invoices.download', $invoice) }}"
                                                class="bg-green-500 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">

                                                Excel

                                            </a>

                                        </div>

                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>

                <div class="mt-6 px-6 pb-6">

                    {{ $commercialInvoices->withQueryString()->links() }}

                </div>

            </div>

        @endif

    </div>

</x-app-layout>
