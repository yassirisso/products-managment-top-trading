@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">New Invoice for {{ $client->name }}</h1>
            <a href="{{ route('clients.show', $client) }}" class="text-gray-600 hover:text-gray-800">
                &larr; Back to Client
            </a>
        </div>

        <form action="{{ route('invoices.store', $client) }}" method="POST" id="invoice-form">
            @csrf

            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="invoice_date" class="block text-gray-700 text-sm font-bold mb-2">Date *</label>
                        <input type="date" name="invoice_date" id="invoice_date"
                            value="{{ old('invoice_date', now()->format('Y-m-d')) }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-gray-700 text-sm font-bold mb-2">Notes</label>
                        <textarea name="notes" id="notes" rows="2"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Items</h3>

                    <div id="items-container">
                        <!-- Dynamic items will be added here -->
                        <div class="item-row grid grid-cols-12 gap-4 mb-4">
                            <div class="col-span-5">
                                <select name="items[0][product_id]"
                                    class="product-select shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                                    <option value="">Select Product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->reference }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-2">
                                <input type="number" name="items[0][quantity]" min="1" value="1"
                                    class="quantity shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                            </div>
                            <div class="col-span-3">
                                <input type="number" step="0.01" name="items[0][unit_price]" placeholder="Price"
                                    class="price shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                            </div>
                            <div class="col-span-2 flex items-center">
                                <button type="button" class="remove-item text-red-500 hover:text-red-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="add-item"
                        class="mt-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded text-sm">
                        + Add Another Item
                    </button>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Create Invoice
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('items-container');
            const addButton = document.getElementById('add-item');
            let itemCount = 1;

            // Add new item row
            addButton.addEventListener('click', function() {
                const newRow = document.createElement('div');
                newRow.className = 'item-row grid grid-cols-12 gap-4 mb-4';
                newRow.innerHTML = `
            <div class="col-span-5">
                <select name="items[${itemCount}][product_id]" 
                        class="product-select shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                    <option value="">Select Product</option>
                    @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->reference }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-2">
                <input type="number" name="items[${itemCount}][quantity]" min="1" value="1" 
                       class="quantity shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"
                       required>
            </div>
            <div class="col-span-3">
                <input type="number" step="0.01" name="items[${itemCount}][unit_price]" placeholder="Price" 
                       class="price shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"
                       required>
            </div>
            <div class="col-span-2 flex items-center">
                <button type="button" class="remove-item text-red-500 hover:text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        `;
                container.appendChild(newRow);
                itemCount++;
            });

            // Remove item row
            container.addEventListener('click', function(e) {
                if (e.target.closest('.remove-item')) {
                    const row = e.target.closest('.item-row');
                    if (container.querySelectorAll('.item-row').length > 1) {
                        row.remove();
                    } else {
                        row.querySelectorAll('input, select').forEach(el => el.value = '');
                    }
                }
            });
        });
    </script>
@endsection
