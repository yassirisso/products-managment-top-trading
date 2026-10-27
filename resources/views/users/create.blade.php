<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800">
            {{ __('messages.add_user') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6 max-w-2xl">
        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.name') }}</label>
                    <input type="text" name="name" class="w-full border rounded-lg px-3 py-2" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.email') }}</label>
                    <input type="email" name="email" class="w-full border rounded-lg px-3 py-2" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.password') }}</label>
                    <input type="password" name="password" class="w-full border rounded-lg px-3 py-2" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.role') }}</label>
                    <select name="role" class="w-full border rounded-lg px-3 py-2">
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label
                        class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.account_status') }}</label>
                    <select name="is_active" class="w-full border rounded-lg px-3 py-2">
                        <option value="1">{{ __('messages.active') }}</option>
                        <option value="0">{{ __('messages.inactive') }}</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('users.index') }}"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800">{{ __('messages.cancel') }}</a>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">
                        {{ __('messages.save_user') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
