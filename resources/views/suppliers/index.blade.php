<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
            {{ __('messages.suppliers_management') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <!-- HEADER -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3 mb-6">
            <h1 class="text-xl md:text-2xl font-bold text-gray-800">
                {{ __('messages.suppliers_management') }}
            </h1>

            <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto min-w-0">
                <form action="{{ route('suppliers.index') }}" method="GET"
                    class="flex w-full sm:flex-1 sm:min-w-0 md:w-auto md:flex-none">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="{{ __('messages.search_supplier') }}"
                        class="flex-1 min-w-0 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    <button type="submit" class="bg-blue-500 text-white px-4 rounded-r-md text-sm">
                        {{ __('messages.search') }}
                    </button>
                </form>

                <a href="{{ route('suppliers.create') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center justify-center text-sm whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ __('messages.new_supplier') }}
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <!-- EMPTY STATE -->
        @if ($suppliers->isEmpty())
            <div class="bg-white shadow-md rounded-lg p-6 text-center text-gray-500 text-sm">
                {{ __('messages.no_suppliers_found') }}
                <a href="{{ route('suppliers.create') }}" class="text-blue-500 hover:underline">
                    {{ __('messages.create_one') }}
                </a>
            </div>
        @else
            <!-- MOBILE / TABLET : CARDS -->
            <div class="lg:hidden space-y-4">
                @foreach ($suppliers as $supplier)
                    <div class="bg-white shadow-md rounded-lg p-4">
                        <div class="flex justify-between items-start gap-2 mb-2">
                            <a href="{{ route('suppliers.clients', $supplier) }}"
                                class="text-blue-600 hover:text-blue-800 font-semibold text-base break-words min-w-0">
                                {{ $supplier->name }}
                            </a>
                            <div class="flex items-center space-x-2 shrink-0">
                                <a href="{{ route('suppliers.show', $supplier) }}"
                                    class="text-blue-600 hover:text-blue-900" title="{{ __('messages.view') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('suppliers.edit', $supplier) }}"
                                    class="text-indigo-600 hover:text-indigo-900" title="{{ __('messages.edit') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <div class="space-y-1 text-sm text-gray-700">
                            <p>
                                <span class="text-gray-500">{{ __('messages.type') }}:</span>
                                {{ ucfirst($supplier->type) }}
                                @if ($supplier->type === 'shop' && $supplier->shop_number)
                                    <span class="text-gray-400 text-xs">({{ $supplier->shop_number }})</span>
                                @endif
                            </p>
                            <p>
                                <span class="text-gray-500">{{ __('messages.clients') }}:</span>
                                {{ $supplier->clients_count }}
                            </p>
                        </div>
                    </div>
                @endforeach
                <div class="pt-2">
                    {{ $suppliers->withQueryString()->links() }}
                </div>
            </div>

            <!-- DESKTOP : TABLE -->
            <div class="hidden lg:block bg-white shadow-md rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.name') }}</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.type') }}</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.clients') }}</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($suppliers as $supplier)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('suppliers.clients', $supplier) }}"
                                            class="text-blue-600 hover:text-blue-800 font-medium">
                                            {{ $supplier->name }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                        {{ ucfirst($supplier->type) }}
                                        @if ($supplier->type === 'shop' && $supplier->shop_number)
                                            <span class="text-gray-400 text-xs">({{ $supplier->shop_number }})</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                        {{ $supplier->clients_count }} {{ __('messages.clients') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end items-center space-x-3">
                                            <a href="{{ route('suppliers.show', $supplier) }}"
                                                class="text-blue-600 hover:text-blue-900"
                                                title="{{ __('messages.view') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('suppliers.edit', $supplier) }}"
                                                class="text-indigo-600 hover:text-indigo-900"
                                                title="{{ __('messages.edit') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path
                                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6 px-6 pb-6">
                    {{ $suppliers->withQueryString()->links() }}
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
