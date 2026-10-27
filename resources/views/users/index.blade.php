<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
            {{ __('messages.users_management') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <!-- TOP -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
            <h1 class="text-xl md:text-2xl font-bold">
                {{ __('messages.users_management') }}
            </h1>

            <a href="{{ route('users.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm text-center whitespace-nowrap">
                {{ __('messages.add_user') }}
            </a>
        </div>

        <!-- SUCCESS -->
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- EMPTY STATE -->
        @if ($users->isEmpty())
            <div class="bg-white shadow-md rounded-lg p-6 text-center text-gray-500 text-sm">
                {{ __('messages.no_users_found') }}
            </div>
        @else
            <!-- MOBILE / TABLET : CARDS -->
            <div class="lg:hidden space-y-4">
                @foreach ($users as $user)
                    <div class="bg-white shadow-md rounded-lg p-4 text-sm">
                        <div class="flex justify-between items-start gap-2 mb-2">
                            <div class="min-w-0">
                                <p class="font-semibold text-gray-900 break-words">{{ $user->name }}</p>
                                <p class="text-gray-500 text-xs break-words">{{ $user->email }}</p>
                            </div>
                            <a href="{{ route('users.edit', $user->id) }}"
                                class="text-blue-600 hover:text-blue-800 text-sm whitespace-nowrap shrink-0">
                                {{ __('messages.edit') }}
                            </a>
                        </div>
                        <div class="flex flex-wrap items-center gap-2 mt-2">
                            @foreach ($user->roles as $role)
                                <span class="px-2 py-1 bg-gray-200 rounded text-xs">{{ $role->name }}</span>
                            @endforeach
                            @if ($user->is_active)
                                <span class="text-green-600 font-semibold text-xs">{{ __('messages.active') }}</span>
                            @else
                                <span class="text-red-600 font-semibold text-xs">{{ __('messages.inactive') }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- DESKTOP : TABLE -->
            <div class="hidden lg:block bg-white shadow-md rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    {{ __('messages.name') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    {{ __('messages.email') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    {{ __('messages.role') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    {{ __('messages.status') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                    {{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $user->email }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($user->roles as $role)
                                                <span
                                                    class="px-2 py-1 bg-gray-200 rounded text-xs">{{ $role->name }}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if ($user->is_active)
                                            <span
                                                class="text-green-600 font-semibold">{{ __('messages.active') }}</span>
                                        @else
                                            <span
                                                class="text-red-600 font-semibold">{{ __('messages.inactive') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm">
                                        <a href="{{ route('users.edit', $user->id) }}"
                                            class="text-blue-600 hover:underline whitespace-nowrap">{{ __('messages.edit') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
