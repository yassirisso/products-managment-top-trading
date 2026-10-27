<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
            {{ __('messages.add_new_warehouse_fee') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6 max-w-3xl">
        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('warehouse-fees.store') }}" method="POST">
                @csrf

                <input type="hidden" name="proforma_invoice_id" id="proforma_invoice_id"
                    value="{{ old('proforma_invoice_id') }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- DATE -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.date') }} *</label>
                        <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required
                            class="w-full border rounded-lg px-3 py-2">
                        @error('date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- CONTAINER -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.container_no') }}
                            *</label>
                        <select name="container_no" id="container_no" required
                            class="w-full border rounded-lg px-3 py-2 select2-container-select">
                            <option value="">{{ __('messages.search_placeholder_container') }}</option>
                            @foreach ($containers as $container)
                                @php
                                    $invId = $proformaInvoices->firstWhere('container_no', $container)?->id;
                                @endphp
                                <option value="{{ $container }}" data-id="{{ $invId }}"
                                    {{ old('container_no') == $container ? 'selected' : '' }}>
                                    {{ $container }}
                                </option>
                            @endforeach
                        </select>
                        @error('container_no')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- CLIENT -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.client') }}
                            *</label>
                        <select name="client_id" required class="w-full border rounded-lg px-3 py-2">
                            <option value="">{{ __('messages.client') }}</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}"
                                    {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- SHIPPING MARK -->
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.shipping_marks') }}</label>
                        <select name="shipping_mark_id" class="w-full border rounded-lg px-3 py-2">
                            <option value="">{{ __('messages.shipping_marks') }}</option>
                            @foreach ($shippingMarks as $mark)
                                <option value="{{ $mark->id }}"
                                    {{ old('shipping_mark_id') == $mark->id ? 'selected' : '' }}>
                                    {{ $mark->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('shipping_mark_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- AMOUNT -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.montant_rmb') }}
                            *</label>
                        <input type="number" step="0.01" min="0" name="montant_rmb"
                            value="{{ old('montant_rmb') }}" required class="w-full border rounded-lg px-3 py-2"
                            placeholder="0.00">
                        @error('montant_rmb')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('warehouse-fees.index') }}"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800">{{ __('messages.cancel') }}</a>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-2 rounded">{{ __('messages.save') }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {

                $('.select2-container-select').select2({
                    placeholder: "{{ __('messages.search_placeholder_container') }}",
                    allowClear: true,
                    tags: true,
                    width: '100%',
                    language: {
                        noResults: function() {
                            return "{{ __('messages.press_enter_to_add') }}";
                        }
                    }
                });

                $('.select2-container-select').on('change', function() {
                    let selectedOption = $(this).find('option:selected');
                    let invId = selectedOption.data('id') || '';
                    $('#proforma_invoice_id').val(invId);
                });

                $('.select2-container-select').trigger('change');
            }
        });
    </script>
</x-app-layout>
