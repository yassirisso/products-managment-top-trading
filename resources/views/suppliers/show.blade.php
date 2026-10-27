<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight break-words">
            {{ $supplier->name }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <!-- HEADER -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-6">
            <div class="min-w-0">
                <h1 class="text-xl md:text-2xl font-bold text-gray-800 break-words">
                    {{ $supplier->name }}
                </h1>
                <p class="text-gray-600 text-sm">
                    {{ __('messages.supplier_details') }}
                </p>
            </div>
            <div class="flex space-x-2 shrink-0">
                <a href="{{ route('suppliers.edit', $supplier) }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded flex items-center"
                    title="{{ __('messages.edit_supplier') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                </a>
                <a href="{{ route('suppliers.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-3 rounded flex items-center"
                    title="{{ __('messages.back_to_suppliers') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- SUPPLIER INFO -->
        <div class="bg-white shadow-md rounded-lg p-4 md:p-6 mb-6">
            <h2 class="text-lg md:text-xl font-semibold mb-4">
                {{ __('messages.supplier_information') }}
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="min-w-0">
                    <p class="text-gray-600 text-sm">{{ __('messages.supplier_name') }}:</p>
                    <p class="font-medium break-words">{{ $supplier->name }}</p>
                </div>
                <div class="min-w-0">
                    <p class="text-gray-600 text-sm">{{ __('messages.type') }}:</p>
                    <p class="font-medium">{{ ucfirst($supplier->type) }}</p>
                </div>
                @if ($supplier->type === 'shop')
                    <div class="min-w-0">
                        <p class="text-gray-600 text-sm">{{ __('messages.shop_number') }}:</p>
                        <p class="font-medium">{{ $supplier->shop_number ?? '—' }}</p>
                    </div>
                @endif
                <div class="min-w-0">
                    <p class="text-gray-600 text-sm">{{ __('messages.email') }}:</p>
                    <p class="font-medium break-words">{{ $supplier->email ?? '—' }}</p>
                </div>
                <div class="min-w-0">
                    <p class="text-gray-600 text-sm">{{ __('messages.phone') }}:</p>
                    <p class="font-medium">{{ $supplier->phone ?? '—' }}</p>
                </div>
                <div class="min-w-0">
                    <p class="text-gray-600 text-sm">{{ __('messages.total_clients') }}:</p>
                    <p class="font-medium">{{ $supplier->products->flatMap->clients->unique('id')->count() }}</p>
                </div>
            </div>
        </div>

        <!-- PRODUCTS -->
        <div class="bg-white shadow-md rounded-lg p-4 md:p-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-4">
                <h2 class="text-lg md:text-xl font-semibold">{{ __('messages.products_supplied') }}</h2>
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
                <p class="text-gray-500 text-sm">{{ __('messages.no_products_associated') }}</p>
            @else
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
                                        <a href="{{ route('products.edit', $product) }}"
                                            class="text-blue-500 hover:text-blue-700"
                                            title="{{ __('messages.edit') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path
                                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- CONTACTS -->
        <div class="bg-white shadow-md rounded-lg p-4 md:p-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-4">
                <h2 class="text-lg md:text-xl font-semibold">{{ __('messages.contact_persons') }}</h2>
                <button onclick="document.getElementById('addContactModal').classList.remove('hidden')"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex items-center justify-center text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ __('messages.add') }}
                </button>
            </div>

            @if ($supplier->contacts->isEmpty())
                <p class="text-gray-500 text-sm">{{ __('messages.no_contacts_associated') }}</p>
            @else
                <div class="lg:hidden space-y-3">
                    @foreach ($supplier->contacts as $contact)
                        <div class="border rounded-lg p-3 text-sm">
                            <div class="flex justify-between items-start gap-2">
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-900 break-words">{{ $contact->name }}</p>
                                    @if ($contact->position)
                                        <p class="text-gray-500 text-xs">{{ $contact->position }}</p>
                                    @endif
                                </div>
                                <form
                                    action="{{ route('suppliers.contacts.destroy', ['supplier' => $supplier, 'contact' => $contact]) }}"
                                    method="POST" class="inline shrink-0">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700"
                                        title="{{ __('messages.remove_contact') }}"
                                        onclick="return confirm('Are you sure?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            <div class="mt-2 space-y-1 text-gray-700">
                                <p class="break-words"><span class="text-gray-500">{{ __('messages.phone') }}:</span>
                                    {{ $contact->phone ?? '—' }}</p>
                                <p class="break-words"><span class="text-gray-500">{{ __('messages.email') }}:</span>
                                    {{ $contact->email ?? '—' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="hidden lg:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.name') }}</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.position') }}</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.phone') }}</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.email') }}</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($supplier->contacts as $contact)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $contact->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $contact->position ?? '—' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $contact->phone ?? '—' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $contact->email ?? '—' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <form
                                            action="{{ route('suppliers.contacts.destroy', ['supplier' => $supplier, 'contact' => $contact]) }}"
                                            method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700"
                                                title="{{ __('messages.remove_contact') }}"
                                                onclick="return confirm('Are you sure?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- ADD PRODUCT MODAL -->
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
                        @foreach (App\Models\Product::whereDoesntHave('suppliers', function ($query) use ($supplier) {
        $query->where('supplier_id', $supplier->id);
    })->get() as $product)
                            <option value="{{ $product->id }}">{{ $product->reference }} - {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="buying_price"
                        class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.buying_price') }} *</label>
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

    <!-- ADD CONTACT MODAL -->
    <div id="addContactModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 md:top-20 mx-auto p-5 border w-[92%] max-w-md shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">{{ __('messages.add_contact_person') }}</h3>
                <button onclick="document.getElementById('addContactModal').classList.add('hidden')"
                    class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>
            <form action="{{ route('suppliers.contacts.store', $supplier) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="contact_name"
                        class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.name') }} *</label>
                    <input type="text" name="name" id="contact_name" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="contact_position"
                        class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.position') }}</label>
                    <input type="text" name="position" id="contact_position"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="contact_phone"
                        class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.phone') }}</label>
                    <input type="text" name="phone" id="contact_phone"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="contact_email"
                        class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.email') }}</label>
                    <input type="email" name="email" id="contact_email"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
                    <button type="button"
                        onclick="document.getElementById('addContactModal').classList.add('hidden')"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded w-full sm:w-auto">{{ __('messages.cancel') }}</button>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full sm:w-auto">{{ __('messages.add') }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.onclick = function(event) {
            ['addProductModal', 'addContactModal'].forEach(function(id) {
                const modal = document.getElementById(id);
                if (modal && event.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        }
    </script>
</x-app-layout>
