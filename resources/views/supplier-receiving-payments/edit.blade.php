<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
            {{ __('messages.edit') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6 max-w-2xl">
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('supplier-receiving-payments.update', $supplierReceivingPayment) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block mb-1 font-medium">{{ __('messages.receiving_notes') }} *</label>

                    <select name="receiving_note_id" id="receiving_note_id" required
                        class="w-full border rounded-lg px-3 py-2">
                        @foreach ($receivingNotes as $note)
                            <option value="{{ $note->id }}"
                                {{ old('receiving_note_id', $supplierReceivingPayment->receiving_note_id) == $note->id ? 'selected' : '' }}>
                                #{{ $note->id }} — {{ $note->proformaInvoice?->client?->name }}
                                ({{ $note->date?->format('Y-m-d') }})
                            </option>
                        @endforeach
                    </select>

                    @error('receiving_note_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="supplier_id" class="block mb-1 font-medium">{{ __('messages.supplier') }} *</label>
                    <select name="supplier_id" id="supplier_id" required class="w-full border rounded-lg px-3 py-2">
                        <option value="">—</option>
                    </select>
                    @error('supplier_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="amount" class="block mb-1 font-medium">{{ __('messages.amount') }} *</label>
                        <input type="number" step="0.01" min="0" name="amount" id="amount" required
                            value="{{ old('amount', $supplierReceivingPayment->amount) }}"
                            class="w-full border rounded-lg px-3 py-2">
                        @error('amount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="currency" class="block mb-1 font-medium">{{ __('messages.currency') }} *</label>
                        <select name="currency" id="currency" required class="w-full border rounded-lg px-3 py-2">
                            <option value="RMB"
                                {{ old('currency', $supplierReceivingPayment->currency) == 'RMB' ? 'selected' : '' }}>
                                RMB</option>
                            <option value="USD"
                                {{ old('currency', $supplierReceivingPayment->currency) == 'USD' ? 'selected' : '' }}>
                                USD</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="payment_date" class="block mb-1 font-medium">{{ __('messages.date') }} *</label>
                    <input type="date" name="payment_date" id="payment_date" required
                        value="{{ old('payment_date', $supplierReceivingPayment->payment_date->format('Y-m-d')) }}"
                        class="w-full border rounded-lg px-3 py-2">
                    @error('payment_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="note" class="block mb-1 font-medium">{{ __('messages.note') }}</label>
                    <textarea name="note" id="note" rows="3" class="w-full border rounded-lg px-3 py-2">{{ old('note', $supplierReceivingPayment->note) }}</textarea>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <a href="{{ route('supplier-receiving-payments.index') }}"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800">{{ __('messages.cancel') }}</a>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-2 rounded">{{ __('messages.update') }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const noteSuppliers = {
            @foreach ($receivingNotes as $note)
                "{{ $note->id }}": [
                    @foreach ($note->products->pluck('suppliers')->flatten()->unique('id') as $s)
                        {
                            id: {{ $s->id }},
                            name: "{{ addslashes($s->name) }}"
                        },
                    @endforeach
                ],
            @endforeach
        };

        function populateSuppliers(noteId, currentSupplierId = null) {
            const supplierSelect = document.getElementById('supplier_id');
            if (!supplierSelect) return;

            supplierSelect.innerHTML = '<option value="">—</option>';

            if (noteId && noteSuppliers[noteId]) {
                noteSuppliers[noteId].forEach(function(s) {
                    const opt = document.createElement('option');
                    opt.value = s.id;
                    opt.text = s.name;
                    if (currentSupplierId && String(s.id) === String(currentSupplierId)) {
                        opt.selected = true;
                    }
                    supplierSelect.appendChild(opt);
                });
            }
        }

        document.getElementById('receiving_note_id')?.addEventListener('change', function() {
            populateSuppliers(this.value);
        });

        document.addEventListener('DOMContentLoaded', function() {
            const noteSelect = document.getElementById('receiving_note_id');
            const currentSupplier = "{{ $supplierReceivingPayment->supplier_id ?? '' }}";
            if (noteSelect) populateSuppliers(noteSelect.value, currentSupplier);
        });
    </script>

</x-app-layout>
