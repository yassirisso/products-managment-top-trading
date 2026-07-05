<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Clients of Supplier: {{ $supplier->name }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <!-- BACK -->
        <div class="mb-4">
            <a href="{{ route('suppliers.index') }}"
               class="text-blue-600 hover:underline">
                ← Back to Suppliers
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Client
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Products
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                            Action
                        </th>

                    </tr>

                </thead>

                <tbody class="bg-white divide-y divide-gray-200">

                    @forelse($clients as $client)

                        <tr class="hover:bg-gray-50">

                            <!-- CLIENT NAME -->
                            <td class="px-6 py-4">
                                {{ $client->name }}
                            </td>

                            <!-- PRODUCTS COUNT -->
                            <td class="px-6 py-4">
                                {{ $client->products_count }}
                            </td>

                            <!-- ACTION -->
                            <td class="px-6 py-4 text-right">

                                <a href="#"
                                   class="text-blue-600 hover:underline">

                                    View Products

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="3" class="text-center py-4 text-gray-500">
                                No clients found for this supplier.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</x-app-layout>