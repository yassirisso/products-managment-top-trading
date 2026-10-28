<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
            {{ __('messages.create_new_product') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <h1 class="text-xl md:text-2xl font-bold text-gray-800 break-words">
                {{ __('messages.create_new_product') }}
            </h1>
            <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-800 text-sm whitespace-nowrap">
                &larr; {{ __('messages.products_management') }}
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg p-4 md:p-6">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Product Image Field -->
                    <div class="min-w-0">
                        <label for="image"
                            class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.product_picture') }}</label>
                        <input type="file" name="image" id="image" accept="image/*"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        @error('image')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Product Reference Field -->
                    <div class="min-w-0">
                        <label for="reference"
                            class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.product_reference') }}
                            *</label>
                        <input type="text" name="reference" id="reference" value="{{ old('reference') }}" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="PRD-001">
                        @error('reference')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Product Price Field -->
                    <div class="min-w-0">
                        <label for="price"
                            class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.product_price') }}</label>
                        <input type="text" name="price" id="price" value="{{ old('price') }}" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="5.60">
                        @error('price')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- PCS/CTS -->
                    <div class="min-w-0">
                        <label for="pcs_cts"
                            class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.pcs_cts') }}</label>
                        <input type="number" name="pcs_cts" id="pcs_cts" value="{{ old('pcs_cts') }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- UNIT CBM -->
                    <div class="min-w-0">
                        <label for="unit_cbm"
                            class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.unit_cbm') }}</label>
                        <input type="number" step="0.001" name="unit_cbm" id="unit_cbm"
                            value="{{ old('unit_cbm') }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- UNIT GW -->
                    <div class="min-w-0">
                        <label for="unit_gw"
                            class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.unit_gw') }}</label>
                        <input type="number" step="0.01" name="unit_gw" id="unit_gw" value="{{ old('unit_gw') }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- UNIT NW -->
                    <div class="min-w-0">
                        <label for="unit_nw"
                            class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.unit_nw') }}</label>
                        <input type="number" step="0.01" name="unit_nw" id="unit_nw" value="{{ old('unit_nw') }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Supplier Field -->
                    <div class="min-w-0">
                        <label for="supplier_id"
                            class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.supplier') }} *</label>
                        <select name="supplier_id" id="supplier_id" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">{{ __('messages.select_supplier') }}</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description (full width) -->
                    <div class="sm:col-span-2 lg:col-span-4 min-w-0">
                        <label for="description"
                            class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.description') }}</label>
                        <textarea name="description" id="description" rows="3"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-2 sm:gap-4 mt-6">
                    <a href="{{ route('products.index') }}"
                        class="text-gray-600 hover:text-gray-800 text-center sm:text-left py-2">{{ __('messages.cancel') }}</a>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 w-full sm:w-auto">
                        {{ __('messages.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
