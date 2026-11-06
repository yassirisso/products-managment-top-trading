<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('messages.client_details') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <!-- Client Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">{{ __('messages.client_details') }}</h1>
            <div class="flex space-x-2">
                <a href="{{ route('clients.edit', $client) }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    {{ __('messages.edit_client') }}
                </a>
            </div>
        </div>

        <!-- Client Info Card -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Left Side -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">{{ $client->name }}</h2>
                        <p class="text-gray-600 mt-2">📍 {{ $client->address ?? __('messages.no_address') }}</p>
                        <p class="text-gray-600 mt-1">📞 {{ $client->phone ?? __('messages.no_phone') }}</p>
                        <p class="text-gray-600 mt-1">✉️ {{ $client->email ?? __('messages.no_email') }}</p>
                        <p class="text-gray-600 mt-1">ICE: {{ $client->ice_number ?? '—' }}</p>
                        <p class="text-gray-600 mt-1">{{ __('messages.fax') }}: {{ $client->fax ?? '—' }}</p>
                        <p class="text-gray-600 mt-1">{{ __('messages.shipping_marks') }}:
                            {{ $client->shipping_mark ?? '—' }}

                        </p>
                    </div>
                </div>
                <!-- Right Side -->
                <div class="md:text-right mt-4 md:mt-0">
                    <p class="text-sm text-gray-600">{{ __('messages.last_updated_time') }}:
                        {{ $client->updated_at->diffForHumans() }}</p>
                    <p class="text-gray-600 mt-1">{{ __('messages.registered') }}:
                        {{ $client->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Payments History -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden mt-6">
            <div
                class="px-4 md:px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                <h3 class="text-lg font-medium text-gray-900">
                    {{ __('messages.client_payments') }}
                </h3>
                @can('create client payments')
                    <a href="{{ route('client-payments.create') }}"
                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm flex items-center justify-center whitespace-nowrap w-full sm:w-auto">
                        + {{ __('messages.new_client_payment') }}
                    </a>
                @endcan
            </div>

            <div class="px-4 md:px-6 py-4 flex flex-wrap gap-4 md:gap-6 text-sm border-b border-gray-100">
                <div>
                    <span class="text-gray-500">{{ __('messages.total') }}:</span>
                    <strong>{{ number_format($totalInvoiced, 2) }}</strong>
                </div>
                <div>
                    <span class="text-gray-500">{{ __('messages.paid') }}:</span>
                    <strong class="text-green-600">{{ number_format($totalPaid, 2) }}</strong>
                </div>
                <div>
                    <span class="text-gray-500">{{ __('messages.remaining') }}:</span>
                    <strong class="text-red-600">{{ number_format($remaining, 2) }}</strong>
                </div>
            </div>

            @if ($payments->isEmpty())
                <div class="px-4 md:px-6 py-4 text-center text-gray-500 text-sm">
                    {{ __('messages.no_client_payments_found') }}
                </div>
            @else
                <!-- MOBILE / TABLET : CARDS -->
                <div class="lg:hidden divide-y divide-gray-200">
                    @foreach ($payments as $payment)
                        <div class="px-4 py-3 text-sm">
                            <div class="flex justify-between items-start gap-2">
                                <span class="font-semibold text-gray-900">
                                    {{ number_format($payment->amount, 2) }} {{ $payment->currency }}
                                </span>
                                <span class="text-gray-500 text-xs shrink-0">
                                    {{ $payment->payment_date->format('Y-m-d') }}
                                </span>
                            </div>
                            <p class="text-gray-700 mt-1">
                                <span class="text-gray-500">{{ __('messages.proforma_invoices') }}:</span>
                                @if ($payment->proformaInvoice)
                                    <a href="{{ route('proforma-invoices.show', $payment->proformaInvoice) }}"
                                        class="text-blue-600 hover:underline">
                                        #{{ $payment->proformaInvoice->id }}
                                    </a>
                                @else
                                    —
                                @endif
                            </p>
                            @if ($payment->note)
                                <p class="text-gray-600 text-xs mt-1 break-words">
                                    <span class="text-gray-500">{{ __('messages.note') }}:</span> {{ $payment->note }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- DESKTOP : TABLE -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                    {{ __('messages.date') }}</th>
                                <th class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                    {{ __('messages.proforma_invoices') }}</th>
                                <th class="px-6 py-2 text-right text-xs font-medium text-gray-500 uppercase">
                                    {{ __('messages.amount') }}</th>
                                <th class="px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                    {{ __('messages.note') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($payments as $payment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        {{ $payment->payment_date->format('Y-m-d') }}</td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        @if ($payment->proformaInvoice)
                                            <a href="{{ route('proforma-invoices.show', $payment->proformaInvoice) }}"
                                                class="text-blue-600 hover:underline">
                                                #{{ $payment->proformaInvoice->id }}
                                            </a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 text-right font-medium">
                                        {{ number_format($payment->amount, 2) }}
                                        {{ $payment->currency }}</td>
                                    <td class="px-6 py-3">{{ $payment->note ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
