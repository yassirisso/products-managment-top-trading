<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('messages.add_bank_account') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6 max-w-3xl">
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('bank-accounts.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- BENEFICIARY -->
                    <div>
                        <label class="block mb-1 font-medium">{{ __('messages.beneficiary_name') }} *</label>
                        <input type="text" name="beneficiary_name" required class="w-full border rounded-lg px-3 py-2">
                    </div>

                    <!-- ACCOUNT -->
                    <div>
                        <label class="block mb-1 font-medium">{{ __('messages.account_number') }} *</label>
                        <input type="text" name="account_number" required class="w-full border rounded-lg px-3 py-2">
                    </div>

                    <!-- SWIFT -->
                    <div>
                        <label class="block mb-1 font-medium">{{ __('messages.swift') }} *</label>
                        <input type="text" name="swift" required class="w-full border rounded-lg px-3 py-2">
                    </div>

                    <!-- BANK -->
                    <div>
                        <label class="block mb-1 font-medium">{{ __('messages.bank_name') }} *</label>
                        <input type="text" name="bank_name" required class="w-full border rounded-lg px-3 py-2">
                    </div>

                    <!-- ADDRESS -->
                    <div class="md:col-span-2">
                        <label class="block mb-1 font-medium">{{ __('messages.bank_address') }} *</label>
                        <textarea name="bank_address" rows="4" required class="w-full border rounded-lg px-3 py-2"></textarea>
                    </div>
                </div>

                <!-- BUTTON -->
                <div class="flex justify-end gap-2 mt-6">
                    <a href="{{ route('bank-accounts.index') }}"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800">{{ __('messages.cancel') }}</a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-2 rounded">
                        {{ __('messages.save_bank') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
