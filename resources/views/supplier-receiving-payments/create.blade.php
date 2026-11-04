<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
            {{ __('messages.new_supplier_payment') }}</h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6 max-w-2xl">
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('supplier-receiving-payments.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="receiving_note_id" class="block mb-1 font-medium">{{ __('messages.receiving_notes') }}
                        *</label>
                    <select name="receiving_note_id" id="receiving_note_id" required
                        class="w-full border rounded-lg px-3 py-2">
                        <option value="">—</option>
                        @foreach ($receivingNotes as $note)
                            <option value="{{ $note->id }}"
                                {{ old('receiving_note_id') == $note->id ? 'selected' : '' }}>
                                #{{ $note->id }} — {{ $note->proformaInvoice?->client?->name }}
                                ({{ $note->date?->format('Y-m-d') }})
                            </option>
                        @endforeach
                    </select>
                    @error('receiving_note_id')
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

                <div class="flex justify-end gap-2 mt-6">
                    <a href="{{ route('supplier-receiving-payments.index') }}"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800">{{ __('messages.cancel') }}</a>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-2 rounded">{{ __('messages.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
