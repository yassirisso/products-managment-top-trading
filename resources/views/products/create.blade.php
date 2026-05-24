<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

            {{ isset($product) ? 'Edit Product: ' . $product->reference : 'Create New Product' }}

        </h2>

    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <div class="flex items-center justify-between mb-6">

            <h1 class="text-2xl font-bold text-gray-800">

                {{ isset($product) ? 'Edit Product: ' . $product->reference : 'Create New Product' }}

            </h1>

            <a href="{{ route('products.index') }}"
               class="text-gray-600 hover:text-gray-800">

                &larr; Back to Products

            </a>

        </div>

        <div class="bg-white shadow-md rounded-lg p-6">

            <form action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                @if (isset($product))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-4 gap-4">

                    <!-- Product Image Field -->
                    <div>

                        <label for="image"
                               class="block text-gray-700 text-sm font-bold mb-2">

                            Product Picture

                        </label>

                        <input type="file"
                               name="image"
                               id="image"
                               accept="image/*"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">

                        @error('image')

                            <p class="text-red-500 text-xs italic mt-1">

                                {{ $message }}

                            </p>

                        @enderror

                    </div>

                    <!-- Product Reference Field -->
                    <div>

                        <label for="reference"
                               class="block text-gray-700 text-sm font-bold mb-2">

                            Product Reference *

                        </label>

                        <input type="text"
                               name="reference"
                               id="reference"
                               value="{{ old('reference', $product->reference ?? '') }}"
                               required
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="PRD-001">

                        @error('reference')

                            <p class="text-red-500 text-xs italic mt-1">

                                {{ $message }}

                            </p>

                        @enderror

                    </div>

                    <!-- Product Price Field -->
                    <div>

                        <label for="price"
                               class="block text-gray-700 text-sm font-bold mb-2">

                            Product Price

                        </label>

                        <input type="text"
                               name="price"
                               id="price"
                               value="{{ old('price', $product->price ?? '') }}"
                               required
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="5.60">

                        @error('price')

                            <p class="text-red-500 text-xs italic mt-1">

                                {{ $message }}

                            </p>

                        @enderror

                    </div>

                    <!-- PCS/CTS -->
                    <div>

                        <label for="pcs_cts"
                               class="block text-gray-700 text-sm font-bold mb-2">

                            PCS/CTS

                        </label>

                        <input type="number"
                               name="pcs_cts"
                               id="pcs_cts"
                               value="{{ old('pcs_cts', $product->pcs_cts ?? '') }}"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">

                    </div>

                    <!-- UNIT CBM -->
                    <div>

                        <label for="unit_cbm"
                               class="block text-gray-700 text-sm font-bold mb-2">

                            UNIT CBM

                        </label>

                        <input type="number"
                               step="0.001"
                               name="unit_cbm"
                               id="unit_cbm"
                               value="{{ old('unit_cbm', $product->unit_cbm ?? '') }}"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">

                    </div>

                    <!-- UNIT GW -->
                    <div>

                        <label for="unit_gw"
                               class="block text-gray-700 text-sm font-bold mb-2">

                            UNIT GW

                        </label>

                        <input type="number"
                               step="0.01"
                               name="unit_gw"
                               id="unit_gw"
                               value="{{ old('unit_gw', $product->unit_gw ?? '') }}"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">

                    </div>

                    <!-- UNIT NW -->
                    <div>

                        <label for="unit_nw"
                               class="block text-gray-700 text-sm font-bold mb-2">

                            UNIT NW

                        </label>

                        <input type="number"
                               step="0.01"
                               name="unit_nw"
                               id="unit_nw"
                               value="{{ old('unit_nw', $product->unit_nw ?? '') }}"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">

                    </div>

                    <!-- Description -->
                    <div class="mb-4">

                        <label for="description"
                               class="block text-gray-700 text-sm font-bold mb-2">

                            Description

                        </label>

                        <textarea
                            name="description"
                            id="description"
                            rows="3"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >{{ old('description', $product->description ?? '') }}</textarea>

                    </div>

                </div>

                <div class="flex items-center justify-end space-x-4 mt-6">

                    <a href="{{ route('products.index') }}"
                       class="text-gray-600 hover:text-gray-800">

                        Cancel

                    </a>

                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">

                        {{ isset($product) ? 'Update Product' : 'Create Product' }}

                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>