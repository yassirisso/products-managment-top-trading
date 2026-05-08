@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Product: {{ $product->reference }}</h1>
        <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Back to Products
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
    
            <div class="grid grid-cols-4 gap-4">
                <!-- Product Image -->
                <div>
                    <label for="image" class="block text-gray-700 text-sm font-bold mb-2">
                        Product Image
                    </label>

                    <input type="file"
                        name="image"
                        id="image"
                        accept="image/*"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">

                    @error('image')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror

                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}"
                            alt="Product Image"
                            class="mt-3 w-24 h-24 object-cover rounded border">
                    @endif
                </div>

                <!-- Product Reference -->
                <div>
                    <label for="reference" class="block text-gray-700 text-sm font-bold mb-2">
                        Product Reference *
                    </label>

                    <input type="text"
                        name="reference"
                        id="reference"
                        value="{{ old('reference', $product->reference) }}"
                        required
                        placeholder="PRD-001"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">

                    @error('reference')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Product Price -->
                <div>
                    <label for="price" class="block text-gray-700 text-sm font-bold mb-2">
                        Price *
                    </label>

                    <input type="number"
                        step="0.01"
                        name="price"
                        id="price"
                        value="{{ old('price', $product->price) }}"
                        required
                        placeholder="0.00"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">

                    @error('price')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- PCS/CTS -->
                <div>
                    <label for="pcs_cts" class="block text-gray-700 text-sm font-bold mb-2">
                        PCS/CTS
                    </label>

                    <input type="number"
                        name="pcs_cts"
                        id="pcs_cts"
                        value="{{ old('pcs_cts', $product->pcs_cts) }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- UNIT CBM -->
                <div>
                    <label for="unit_cbm" class="block text-gray-700 text-sm font-bold mb-2">
                        UNIT CBM
                    </label>

                    <input type="number"
                        step="0.001"
                        name="unit_cbm"
                        id="unit_cbm"
                        value="{{ old('unit_cbm', $product->unit_cbm) }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- UNIT GW -->
                <div>
                    <label for="unit_gw" class="block text-gray-700 text-sm font-bold mb-2">
                        UNIT GW
                    </label>

                    <input type="number"
                        step="0.01"
                        name="unit_gw"
                        id="unit_gw"
                        value="{{ old('unit_gw', $product->unit_gw) }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- UNIT NW -->
                <div>
                    <label for="unit_nw" class="block text-gray-700 text-sm font-bold mb-2">
                        UNIT NW
                    </label>

                    <input type="number"
                        step="0.01"
                        name="unit_nw"
                        id="unit_nw"
                        value="{{ old('unit_nw', $product->unit_nw) }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">
                        Description
                    </label>

                    <textarea
                        name="description"
                        id="description"
                        rows="1"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >{{ old('description', $product->description) }}</textarea>
                </div>
            </div>
    
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-800 flex items-center">
                    Cancel
                </a>
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center">
                    Update Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection