<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">

            Packing Lists

        </h2>

    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <!-- HEADER -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3 mb-6">

            <h1 class="text-xl md:text-2xl font-bold">

                Packing Lists

            </h1>

            <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto min-w-0">

                <!-- Search Form -->
                <form action="{{ route('packing-lists.index') }}" method="GET"
                    class="flex w-full sm:flex-1 sm:min-w-0 md:w-auto md:flex-none">

                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search packing list..."
                        class="flex-1 min-w-0 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">

                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 rounded-r-md text-sm">

                        Search

                    </button>

                </form>

                <!-- Create Button -->
                <a href="{{ route('packing-lists.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm text-center whitespace-nowrap">

                    + Create Packing List

                </a>

            </div>

        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">

                <p>{{ session('success') }}</p>

            </div>
        @endif

        <!-- EMPTY STATE -->
        @if ($packingLists->isEmpty())

            <div class="bg-white shadow-md rounded-lg p-6 text-center text-gray-500 text-sm">

                No packing lists found.

                <a href="{{ route('packing-lists.create') }}" class="text-blue-500 hover:underline">

                    Create one

                </a>

            </div>
        @else
            <!-- MOBILE / TABLET : CARDS -->
            <div class="lg:hidden space-y-4">

                @foreach ($packingLists as $list)
                    <div class="bg-white shadow-md rounded-lg p-4">

                        <!-- TOP ROW: CLIENT + ACTIONS -->
                        <div class="flex justify-between items-start gap-2 mb-2">

                            <a href="{{ route('packing-lists.show', $list->id) }}"
                                class="text-blue-600 hover:text-blue-800 font-semibold text-base break-words min-w-0">

                                {{ $list->client->name ?? '-' }}

                            </a>

                            <div class="flex items-center space-x-2 shrink-0">

                                <a href="{{ route('packing-lists.show', $list->id) }}"
                                    class="text-blue-600 hover:text-blue-900" title="View">

                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />

                                    </svg>

                                </a>

                                <a href="{{ route('packing-lists.edit', $list->id) }}"
                                    class="text-indigo-600 hover:text-indigo-900" title="Edit">

                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">

                                        <path
                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />

                                    </svg>

                                </a>

                            </div>

                        </div>

                        <!-- DETAILS -->
                        <div class="grid grid-cols-2 gap-2 text-sm text-gray-700">

                            <p><span class="text-gray-500">Date:</span> {{ $list->date }}</p>
                            <p><span class="text-gray-500">Container:</span> {{ $list->container_no ?? '—' }}</p>
                            <p class="col-span-2"><span class="text-gray-500">Seal No:</span>
                                {{ $list->seal_no ?? '—' }}</p>

                        </div>

                    </div>
                @endforeach

                <div class="pt-2">

                    {{ $packingLists->withQueryString()->links() }}

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
                                    Client
                                </th>

                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>

                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Container
                                </th>

                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Seal No
                                </th>

                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>

                            </tr>

                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">

                            @foreach ($packingLists as $list)
                                <tr class="hover:bg-gray-50">

                                    <td class="px-6 py-4 whitespace-nowrap">

                                        <a href="{{ route('packing-lists.show', $list->id) }}"
                                            class="text-sm font-medium text-blue-600 hover:text-blue-800">

                                            {{ $list->client->name ?? '-' }}

                                        </a>

                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $list->date }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $list->container_no }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $list->seal_no }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">

                                        <div class="flex justify-end space-x-2">

                                            <a href="{{ route('packing-lists.show', $list->id) }}"
                                                class="text-blue-600 hover:text-blue-900" title="View">

                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">

                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />

                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />

                                                </svg>

                                            </a>

                                            <a href="{{ route('packing-lists.edit', $list->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900" title="Edit">

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

                    {{ $packingLists->withQueryString()->links() }}

                </div>

            </div>

        @endif

    </div>

</x-app-layout>
