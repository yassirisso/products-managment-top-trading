<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">{{ __('messages.new_client_payment') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6 max-w-2xl">
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('client-payments.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label class="block mb-1 font-medium">{{ __('messages.proforma_invoices') }} *</label>

                    @if ($lockedInvoice)
                        <div class="w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-700">
                            #{{ $lockedInvoice->id }} — {{ $lockedInvoice->client?->name }} ({{ $lockedInvoice->date }})
                        </div>
                        <input type="hidden" name="proforma_invoice_id" value="{{ $lockedInvoice->id }}">
                    @else
                        <select name="proforma_invoice_id" id="proforma_invoice_id" required
                            class="w-full border rounded-lg px-3 py-2">
                            <option value="">—</option>
                            @foreach ($proformaInvoices as $invoice)
                                <option value="{{ $invoice->id }}"
                                    {{ old('proforma_invoice_id', request('proforma_invoice_id')) == $invoice->id ? 'selected' : '' }}>
                                    #{{ $invoice->id }} — {{ $invoice->client?->name }} ({{ $invoice->date }})
                                </option>
                            @endforeach
                        </select>
                    @endif

                    @error('proforma_invoice_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="amount" class="block mb-1 font-medium">{{ __('messages.amount') }} *</label>
                        <input type="number" step="0.01" min="0" name="amount" id="amount" required
                            value="{{ old('amount') }}" class="w-full border rounded-lg px-3 py-2">
                        @error('amount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="currency" class="block mb-1 font-medium">{{ __('messages.currency') }} *</label>
                        <select name="currency" id="currency" required class="w-full border rounded-lg px-3 py-2">
                            <option value="RMB" {{ old('currency') == 'RMB' ? 'selected' : '' }}>RMB</option>
                            <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="payment_date" class="block mb-1 font-medium">{{ __('messages.date') }} *</label>
                    <input type="date" name="payment_date" id="payment_date" required
                        value="{{ old('payment_date', date('Y-m-d')) }}" class="w-full border rounded-lg px-3 py-2">
                    @error('payment_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="note" class="block mb-1 font-medium">{{ __('messages.note') }}</label>
                    <textarea name="note" id="note" rows="3" class="w-full border rounded-lg px-3 py-2">{{ old('note') }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="screenshot" class="block mb-1 font-medium">{{ __('messages.screenshot') }}</label>
                    <input type="file" name="screenshot" id="screenshot" accept="image/*"
                        class="w-full border rounded-lg px-3 py-2 text-sm">
                    @error('screenshot')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <a href="{{ route('client-payments.index') }}"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800">{{ __('messages.cancel') }}</a>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-2 rounded">{{ __('messages.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
