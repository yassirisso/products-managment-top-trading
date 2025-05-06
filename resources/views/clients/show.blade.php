@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Client Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Client Details</h1>
            <div class="flex space-x-2">
                <a href="{{ route('clients.edit', $client) }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Edit Client
                </a>
            </div>
        </div>

        <!-- Client Info Card -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">{{ $client->name }}</h2>
                        <p class="text-gray-600 mt-1">Registered: {{ $client->created_at->format('M d, Y') }}</p>
                    </div>
                    <div class="md:text-right">
                        <p class="text-sm text-gray-600">Last updated: {{ $client->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoices Section -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">History</h3>
                <a href="{{ route('invoices.create', $client) }}"
                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    New Invoice
                </a>
            </div>

            @forelse($invoices as $invoice)
                <div class="border-b border-gray-200 px-6 py-4 hover:bg-gray-50">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h4 class="font-medium">Invoice #{{ $invoice->id }}</h4>
                            <p class="text-sm text-gray-500">{{ $invoice->invoice_date->format('M d, Y') }}</p>
                        </div>
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm">
                            ${{ number_format($invoice->total_amount, 2) }}
                        </span>
                    </div>

                    <div class="mt-2 space-y-1">
                        @foreach ($invoice->items as $item)
                            <div class="flex justify-between text-sm">
                                <span>{{ $item->product->reference }}</span>
                                <span>${{ number_format($item->unit_price, 2) }} × {{ $item->quantity }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="px-6 py-4 text-center text-gray-500">
                    No invoices found for this client.
                </div>
            @endforelse

            @if ($invoices->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $invoices->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
