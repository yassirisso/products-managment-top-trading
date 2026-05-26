<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

            Commercial Invoices

        </h2>

    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <!-- TOP -->
        <div class="flex justify-between items-center mb-6">

            <h1 class="text-2xl font-bold text-gray-800">

                Commercial Invoices

            </h1>

            <a href="{{ route('commercial-invoices.create') }}"
               class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">

                Create Commercial Invoice

            </a>

        </div>

        <!-- SUCCESS -->
        @if(session('success'))

            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">

                {{ session('success') }}

            </div>

        @endif

        <!-- TABLE -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="px-6 py-3 text-left">

                            ID

                        </th>

                        <th class="px-6 py-3 text-left">

                            Client

                        </th>

                        <th class="px-6 py-3 text-left">

                            Invoice No

                        </th>

                        <th class="px-6 py-3 text-left">

                            Date

                        </th>

                        <th class="px-6 py-3 text-center">

                            Actions

                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-200">

                    @forelse($commercialInvoices as $invoice)

                        <tr>

                            <!-- ID -->
                            <td class="px-6 py-4">

                                {{ $invoice->id }}

                            </td>

                            <!-- CLIENT -->
                            <td class="px-6 py-4">

                                {{ $invoice->client->name }}

                            </td>

                            <!-- INVOICE -->
                            <td class="px-6 py-4">

                                {{ $invoice->invoice_no }}

                            </td>

                            <!-- DATE -->
                            <td class="px-6 py-4">

                                {{ $invoice->date }}

                            </td>

                            <!-- ACTIONS -->
                            <td class="px-6 py-4 text-center">

                                <div class="flex justify-center space-x-2">

                                    <!-- SHOW -->
                                    <a href="{{ route('commercial-invoices.show', $invoice) }}"
                                       class="bg-gray-500 hover:bg-gray-700 text-white px-3 py-1 rounded">

                                        View

                                    </a>

                                    <!-- EXCEL -->
                                    <a href="{{ route('commercial-invoices.download', $invoice) }}"
                                       class="bg-green-500 hover:bg-green-700 text-white px-3 py-1 rounded">

                                        Excel

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5"
                                class="px-6 py-4 text-center text-gray-500">

                                No Commercial Invoices Found

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</x-app-layout>