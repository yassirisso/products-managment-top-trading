@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            {{ isset($product) ? 'Edit Product: ' . $product->reference : 'Create New Product' }}
        </h1>
        <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-800">
            &larr; Back to Products
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form
            action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}"
            method="POST"
        >
            @csrf
            @if(isset($product))
                @method('PUT')
            @endif

            <div class="mb-4">
                <label for="reference" class="block text-gray-700 text-sm font-bold mb-2">Product Reference *</label>
                <input
                    type="text"
                    name="reference"
                    id="reference"
                    value="{{ old('reference', $product->reference ?? '') }}"
                    required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="PRD-001"
                >
                @error('reference')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end space-x-4 mt-6">
                <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-800">
                    Cancel
                </a>
                <button
                    type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    {{ isset($product) ? 'Update Product' : 'Create Product' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection