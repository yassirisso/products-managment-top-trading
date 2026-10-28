<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
            {{ isset($client) ? __('messages.edit_client') . ': ' . $client->name : __('messages.create_new_client') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <h1 class="text-xl md:text-2xl font-bold text-gray-800 break-words">
                {{ isset($client) ? __('messages.edit_client') . ': ' . $client->name : __('messages.create_new_client') }}
            </h1>
            <a href="{{ route('clients.index') }}" class="text-gray-600 hover:text-gray-800 text-sm whitespace-nowrap">
                &larr; {{ __('messages.clients') }}
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg p-4 md:p-6">
            <form action="{{ isset($client) ? route('clients.update', $client->id) : route('clients.store') }}"
                method="POST">
                @csrf
                @if (isset($client))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                    <!-- Name -->
                    <div class="min-w-0">
                        <label for="name"
                            class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.client_name') }}
                            *</label>
                        <input type="text" name="name" id="name"
                            value="{{ old('name', $client->name ?? '') }}" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('name')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div class="min-w-0">
                        <label for="phone"
                            class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.phone') }}</label>
                        <input type="text" name="phone" id="phone"
                            value="{{ old('phone', $client->phone ?? '') }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="+44 7123 456789">
                        @error('phone')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="min-w-0">
                        <label for="email"
                            class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.email') }}</label>
                        <input type="email" name="email" id="email"
                            value="{{ old('email', $client->email ?? '') }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="client@example.com">
                        @error('email')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ICE Number -->
                    <div class="min-w-0">
                        <label for="ice_number"
                            class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.ice_number') }}</label>
                        <input type="text" name="ice_number" id="ice_number"
                            value="{{ old('ice_number', $client->ice_number ?? '') }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="{{ __('messages.ice_number') }}">
                        @error('ice_number')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fax -->
                    <div class="min-w-0">
                        <label for="fax"
                            class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.fax') }}</label>
                        <input type="text" name="fax" id="fax"
                            value="{{ old('fax', $client->fax ?? '') }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="{{ __('messages.fax') }}">
                        @error('fax')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address (full width) -->
                    <div class="md:col-span-2 min-w-0">
                        <label for="address"
                            class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.address') }}</label>
                        <textarea name="address" id="address" rows="3"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="{{ __('messages.address') }}...">{{ old('address', $client->address ?? '') }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-2 sm:gap-4 mt-6">
                    <a href="{{ route('clients.index') }}"
                        class="text-gray-600 hover:text-gray-800 text-center sm:text-left py-2">{{ __('messages.cancel') }}</a>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 w-full sm:w-auto">
                        {{ isset($client) ? __('messages.update_client') : __('messages.create_client') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
