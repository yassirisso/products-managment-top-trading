@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Supplier: {{ $supplier->name }}</h1>
        <a href="{{ route('suppliers.index') }}" class="text-gray-600 hover:text-gray-800">
            &larr; Back to Suppliers
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Supplier Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $supplier->name) }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required autofocus>
                @error('name')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('suppliers.show', $supplier) }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    Cancel
                </a>
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Update Supplier
                </button>
            </div>
        </form>
    </div>

    <!-- Products Section -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Supplier Products</h2>
            <button onclick="document.getElementById('addProductModal').classList.remove('hidden')" 
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Add Product
            </button>
        </div>

        @if($supplier->products->isEmpty())
            <p class="text-gray-500">No products associated with this supplier.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buying Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($supplier->products as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $product->reference }} - {{ $product->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ number_format($product->pivot->buying_price, 2) }}¥
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('products.edit', $product) }}" 
                                       class="text-blue-500 hover:text-blue-700"
                                       title="Edit Product">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('suppliers.detach-product', ['supplier' => $supplier, 'productId' => $product->id]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-500 hover:text-red-700"
                                                title="Remove Product"
                                                onclick="return confirm('Are you sure you want to remove this product?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Add Product Modal -->
    <div id="addProductModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Add Product to Supplier</h3>
                <button onclick="document.getElementById('addProductModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                    &times;
                </button>
            </div>
            <form action="{{ route('suppliers.attach-product', $supplier) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="product_id" class="block text-gray-700 text-sm font-bold mb-2">Product *</label>
                    <select name="product_id" id="product_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select Product</option>
                        @foreach($availableProducts as $product)
                            <option value="{{ $product->id }}">{{ $product->reference }} - {{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="buying_price" class="block text-gray-700 text-sm font-bold mb-2">Buying Price *</label>
                    <input type="number" step="0.01" min="0" name="buying_price" id="buying_price" 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           required>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="document.getElementById('addProductModal').classList.add('hidden')" 
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Add Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Close modal when clicking outside of it
    window.onclick = function(event) {
        const modal = document.getElementById('addProductModal');
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    }
</script>
@endsection