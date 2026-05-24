<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

            Proforma Invoices

        </h2>

    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <div class="flex justify-between items-center mb-6">

            <h1 class="text-2xl font-bold">

                Proforma Invoices

            </h1>

            <a href="{{ route('proforma-invoices.create') }}"
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">

                + Create Proforma Invoice

            </a>

        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">

                                Client

                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">

                                Date

                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">

                                Container

                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">

                                Seal No

                            </th>

                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">

                                Actions

                            </th>

                        </tr>

                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">

                        @forelse($proformaInvoices as $invoice)

                            <tr class="hover:bg-gray-50">

                                <!-- CLIENT -->
                                <td class="px-6 py-4 whitespace-nowrap">

                                    <div class="text-sm font-medium text-gray-900">

                                        {{ $invoice->client->name ?? '-' }}

                                    </div>

                                </td>

                                <!-- DATE -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">

                                    {{ $invoice->date }}

                                </td>

                                <!-- CONTAINER -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">

                                    {{ $invoice->container_no }}

                                </td>

                                <!-- SEAL -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">

                                    {{ $invoice->seal_no }}

                                </td>

                                <!-- ACTIONS -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">

                                    <div class="flex justify-end space-x-2">

                                        <!-- VIEW -->
                                        <a href="{{ route('proforma-invoices.show', $invoice->id) }}"
                                           class="text-blue-600 hover:text-blue-900">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-5 w-5"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor">

                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="2"
                                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />

                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="2"
                                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />

                                            </svg>

                                        </a>

                                        <!-- DOWNLOAD -->
                                        <a href="{{ route('proforma-invoices.download', $invoice->id) }}"
                                           class="text-green-600 hover:text-green-900">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-5 w-5"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor">

                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="2"
                                                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />

                                            </svg>

                                        </a>

                                        <!-- DELETE -->
                                        <form action="{{ route('proforma-invoices.destroy', $invoice->id) }}"
                                              method="POST"
                                              class="inline">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Are you sure?')">

                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-5 w-5"
                                                     viewBox="0 0 20 20"
                                                     fill="currentColor">

                                                    <path fill-rule="evenodd"
                                                          d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                          clip-rule="evenodd" />

                                                </svg>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5"
                                    class="px-6 py-4 text-center text-gray-500">

                                    No proforma invoices found.

                                    <a href="{{ route('proforma-invoices.create') }}"
                                       class="text-blue-500 hover:underline">

                                        Create one

                                    </a>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-app-layout>