<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight break-words">
            {{ __('messages.edit_supplier') }}: {{ $supplier->name }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <!-- HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <h1 class="text-xl md:text-2xl font-bold text-gray-800 break-words">
                {{ __('messages.edit_supplier') }}: {{ $supplier->name }}
            </h1>
            <a href="{{ route('suppliers.index') }}"
                class="text-gray-600 hover:text-gray-800 flex items-center text-sm whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd" />
                </svg>
                {{ __('messages.back_to_suppliers') }}
            </a>
        </div>

        <!-- EDIT FORM -->
        <div class="bg-white shadow-md rounded-lg p-4 md:p-6 mb-6">
            <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        {{ __('messages.supplier_information') }}
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- NAME -->
                        <div class="min-w-0">
                            <input type="text" name="name" id="name"
                                value="{{ old('name', $supplier->name) }}" required autofocus
                                placeholder="{{ __('messages.supplier_name') }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('name')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- TYPE -->
                        <div class="min-w-0">
                            <select name="type" id="type" required onchange="toggleShopNumber()"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="factory"
                                    {{ old('type', $supplier->type) === 'factory' ? 'selected' : '' }}>
                                    {{ __('messages.factory') }}</option>
                                <option value="shop" {{ old('type', $supplier->type) === 'shop' ? 'selected' : '' }}>
                                    {{ __('messages.shop') }}</option>
                            </select>
                            @error('type')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- SHOP NUMBER -->
                        <div class="min-w-0" id="shop_number_wrapper">
                            <input type="text" name="shop_number" id="shop_number"
                                value="{{ old('shop_number', $supplier->shop_number) }}"
                                placeholder="{{ __('messages.shop_number') }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('shop_number')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- EMAIL -->
                        <div class="min-w-0">
                            <input type="email" name="email" id="email"
                                value="{{ old('email', $supplier->email) }}" placeholder="{{ __('messages.email') }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('email')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- PHONE -->
                        <div class="min-w-0">
                            <input type="text" name="phone" id="phone"
                                value="{{ old('phone', $supplier->phone) }}" placeholder="{{ __('messages.phone') }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('phone')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- BUTTONS -->
                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-2 sm:gap-4">
                    <a href="{{ route('suppliers.show', $supplier) }}"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-center w-full sm:w-auto">
                        {{ __('messages.cancel') }}
                    </a>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full sm:w-auto">
                        {{ __('messages.update_supplier') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- PRODUCTS SECTION -->
        <div class="bg-white shadow-md rounded-lg p-4 md:p-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-4">
                <h2 class="text-lg md:text-xl font-semibold">
                    {{ __('messages.products_supplied') }}
                </h2>
                <button onclick="document.getElementById('addProductModal').classList.remove('hidden')"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex items-center justify-center text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ __('messages.add_product') }}
                </button>
            </div>

            @if ($supplier->products->isEmpty())
                <p class="text-gray-500 text-sm">
                    {{ __('messages.no_products_associated') }}
                </p>
            @else
                <!-- MOBILE / TABLET : CARDS -->
                <div class="lg:hidden space-y-3">
                    @foreach ($supplier->products as $product)
                        <div class="border rounded-lg p-3 flex justify-between items-start gap-2 text-sm">
                            <div class="min-w-0">
                                <p class="font-medium text-gray-900 break-words">{{ $product->reference }} -
                                    {{ $product->name }}</p>
                                <p class="text-gray-700 mt-1">{{ __('messages.buying_price') }}: <span
                                        class="font-medium">{{ number_format($product->pivot->buying_price, 2) }}¥</span>
                                </p>
                            </div>
                            <a href="{{ route('products.edit', $product) }}"
                                class="text-blue-500 hover:text-blue-700 shrink-0" title="{{ __('messages.edit') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </a>
                        </div>
                    @endforeach
                </div>

                <!-- DESKTOP : TABLE -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.product') }}</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.buying_price') }}</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($supplier->products as $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $product->reference }} -
                                        {{ $product->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ number_format($product->pivot->buying_price, 2) }}¥</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end items-center space-x-2">
                                            <a href="{{ route('products.edit', $product) }}"
                                                class="text-blue-500 hover:text-blue-700"
                                                title="{{ __('messages.edit') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path
                                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- MODAL -->
        <div id="addProductModal"
            class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-10 md:top-20 mx-auto p-5 border w-[92%] max-w-md shadow-lg rounded-md bg-white">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">{{ __('messages.add_product_to_supplier') }}</h3>
                    <button onclick="document.getElementById('addProductModal').classList.add('hidden')"
                        class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>
                <form action="{{ route('suppliers.attach-product', $supplier) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="product_id"
                            class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.product') }} *</label>
                        <select name="product_id" id="product_id" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">{{ __('messages.product') }}</option>
                            @foreach ($availableProducts as $product)
                                <option value="{{ $product->id }}">{{ $product->reference }} - {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="buying_price"
                            class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.buying_price') }}
                            *</label>
                        <input type="number" step="0.01" min="0" name="buying_price" id="buying_price"
                            required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
                        <button type="button"
                            onclick="document.getElementById('addProductModal').classList.add('hidden')"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded w-full sm:w-auto">{{ __('messages.cancel') }}</button>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full sm:w-auto">{{ __('messages.add') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        window.onclick = function(event) {
            const modal = document.getElementById('addProductModal');
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        }
    </script>
    <script>
        function toggleShopNumber() {
            const typeEl = document.getElementById('type');
            const wrapper = document.getElementById('shop_number_wrapper');
            const shopNumber = document.getElementById('shop_number');
            if (!typeEl || !wrapper) return;
            if (typeEl.value === 'shop') {
                wrapper.classList.remove('hidden');
            } else {
                wrapper.classList.add('hidden');
                if (shopNumber) shopNumber.value = '';
            }
        }
        document.addEventListener('DOMContentLoaded', toggleShopNumber);
    </script>
</x-app-layout>
