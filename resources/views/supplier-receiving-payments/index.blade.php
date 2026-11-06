<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
            {{ __('messages.supplier_receiving_payments') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3 mb-6">
            <h1 class="text-xl md:text-2xl font-bold text-gray-800">{{ __('messages.supplier_receiving_payments') }}</h1>

            <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto min-w-0">
                <form action="{{ route('supplier-receiving-payments.index') }}" method="GET"
                    class="flex w-full sm:flex-1 sm:min-w-0 md:w-auto md:flex-none">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="{{ __('messages.search') }}..."
                        class="flex-1 min-w-0 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 rounded-r-md text-sm">{{ __('messages.search') }}</button>
                </form>

                @can('create supplier payments')
                    <a href="{{ route('supplier-receiving-payments.create') }}"
                        class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm text-center whitespace-nowrap">
                        + {{ __('messages.new_supplier_payment') }}
                    </a>
                @endcan
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
                {{ session('success') }}</div>
        @endif

        @if ($payments->isEmpty())
            <div class="bg-white shadow-md rounded-lg p-6 text-center text-gray-500 text-sm">
                {{ __('messages.no_supplier_payments_found') }}</div>
        @else
            <div class="lg:hidden space-y-4">
                @foreach ($payments as $payment)
                    <div class="bg-white shadow-md rounded-lg p-4 text-sm">
                        <div class="flex justify-between items-start gap-2 mb-2">
                            <span class="font-semibold text-gray-900">{{ number_format($payment->amount, 2) }}
                                {{ $payment->currency }}</span>
                            <span class="text-gray-500 text-xs">{{ $payment->payment_date->format('Y-m-d') }}</span>
                        </div>
                        <div class="space-y-1 text-gray-700 mb-3">
                            <p class="break-words">
                                <span class="text-gray-500">{{ __('messages.supplier') }}:</span>
                                {{ $payment->supplier?->name ?? '—' }}
                            </p>
                            <p class="break-words">
                                <span class="text-gray-500">{{ __('messages.receiving_notes') }}:</span>
                                @if ($payment->receivingNote)
                                    <a href="{{ route('receiving-notes.show', $payment->receivingNote) }}"
                                        class="text-blue-600 hover:underline">#{{ $payment->receivingNote->id }}</a>
                                @else
                                    —
                                @endif
                            </p>
                            @if ($payment->note)
                                <p><span class="text-gray-500">{{ __('messages.note') }}:</span> {{ $payment->note }}
                                </p>
                            @endif
                        </div>
                        <div class="flex justify-end items-center space-x-3 pt-2 border-t">
                            @can('edit supplier payments')
                                <a href="{{ route('supplier-receiving-payments.edit', $payment) }}"
                                    class="text-indigo-600 hover:text-indigo-900" title="{{ __('messages.edit') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </a>
                            @endcan
                            @can('delete supplier payments')
                                <form action="{{ route('supplier-receiving-payments.destroy', $payment) }}" method="POST"
                                    class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                        title="{{ __('messages.delete') }}" onclick="return confirm('Are you sure?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                @endforeach
                <div class="pt-2">{{ $payments->withQueryString()->links() }}</div>
            </div>

            <div class="hidden lg:block bg-white shadow-md rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.date') }}</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.supplier') }}</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.receiving_notes') }}</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.amount') }}</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.note') }}</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($payments as $payment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ $payment->payment_date->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ $payment->supplier?->name ?? '—' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if ($payment->receivingNote)
                                            <a href="{{ route('receiving-notes.show', $payment->receivingNote) }}"
                                                class="text-blue-600 hover:underline">#{{ $payment->receivingNote->id }}</a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        {{ number_format($payment->amount, 2) }} {{ $payment->currency }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $payment->note ?? '—' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end items-center space-x-3">
                                            @can('edit supplier payments')
                                                <a href="{{ route('supplier-receiving-payments.edit', $payment) }}"
                                                    class="text-indigo-600 hover:text-indigo-900"
                                                    title="{{ __('messages.edit') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path
                                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                    </svg>
                                                </a>
                                            @endcan
                                            @can('delete supplier payments')
                                                <form action="{{ route('supplier-receiving-payments.destroy', $payment) }}"
                                                    method="POST" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                                        title="{{ __('messages.delete') }}"
                                                        onclick="return confirm('Are you sure?')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6 px-6 pb-6">{{ $payments->withQueryString()->links() }}</div>
            </div>
        @endif
    </div>
</x-app-layout>
