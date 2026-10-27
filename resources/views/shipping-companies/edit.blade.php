<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('messages.edit_shipping_company') }}: {{ $shippingCompany->name }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <h1 class="text-xl md:text-2xl font-bold text-gray-800 break-words">
                {{ __('messages.edit_shipping_company') }}: {{ $shippingCompany->name }}
            </h1>
            <a href="{{ route('shipping-companies.index') }}"
                class="text-gray-600 hover:text-gray-800 flex items-center text-sm whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd" />
                </svg>
                {{ __('messages.back') }}
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg p-4 md:p-6">
            <form action="{{ route('shipping-companies.update', $shippingCompany) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- NAME -->
                        <div class="min-w-0">
                            <label class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.company_name') }}
                                *</label>
                            <input type="text" name="name" id="name"
                                value="{{ old('name', $shippingCompany->name) }}" required autofocus
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('name')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- TEL -->
                        <div class="min-w-0">
                            <label
                                class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.telephone') }}</label>
                            <input type="text" name="tel" id="tel"
                                value="{{ old('tel', $shippingCompany->tel) }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('tel')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- EMAIL -->
                        <div class="min-w-0">
                            <label
                                class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.email') }}</label>
                            <input type="email" name="email" id="email"
                                value="{{ old('email', $shippingCompany->email) }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('email')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- BUTTONS -->
                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-2 sm:gap-4">
                    <a href="{{ route('shipping-companies.show', $shippingCompany) }}"
                        class="text-gray-600 hover:text-gray-800 text-center sm:text-left py-2">{{ __('messages.cancel') }}</a>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 w-full sm:w-auto">
                        {{ __('messages.update_shipping_company') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
