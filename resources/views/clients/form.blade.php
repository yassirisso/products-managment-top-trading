@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            {{ isset($client) ? 'Edit Client: ' . $client->name : 'Create New Client' }}
        </h1>
        <a href="{{ route('clients.index') }}" class="text-gray-600 hover:text-gray-800">
            &larr; Back to Clients
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form
            action="{{ isset($client) ? route('clients.update', $client->id) : route('clients.store') }}"
            method="POST"
        >
            @csrf
            @if(isset($client))
                @method('PUT')
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Client Name *</label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name', $client->name ?? '') }}"
                        required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Phone Number -->
                <div>
                    <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">
                        Phone Number
                    </label>

                    <input
                        type="text"
                        name="phone"
                        id="phone"
                        value="{{ old('phone', $client->phone ?? '') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="+44 7123 456789"
                    >

                    @error('phone')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Address -->
                <div class="mt-6">
                    <label for="address" class="block text-gray-700 text-sm font-bold mb-2">
                        Address
                    </label>

                    <textarea
                        name="address"
                        id="address"
                        rows="3"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Client address..."
                    >{{ old('address', $client->address ?? '') }}</textarea>

                    @error('address')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="flex items-center justify-end space-x-4 mt-6">
                <a href="{{ route('clients.index') }}" class="text-gray-600 hover:text-gray-800">
                    Cancel
                </a>
                <button
                    type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    {{ isset($client) ? 'Update Client' : 'Create Client' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
