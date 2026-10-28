<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
            {{ __('messages.receiving_notes') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3 mb-6">
            <h1 class="text-xl md:text-2xl font-bold text-gray-800">
                {{ __('messages.receiving_notes') }}
            </h1>

            @can('create receiving notes')
                <a href="{{ route('receiving-notes.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm text-center whitespace-nowrap">
                    + {{ __('messages.create_receiving_note') }}
                </a>
            @endcan
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 text-sm" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if ($receivingNotes->isEmpty())
            <div class="bg-white shadow-md rounded-lg p-6 text-center text-gray-500 text-sm">
                {{ __('messages.no_receiving_notes_found') }}
            </div>
        @else
            <!-- MOBILE / TABLET : CARDS -->
            <div class="lg:hidden space-y-4">
                @foreach ($receivingNotes as $note)
                    <div class="bg-white shadow-md rounded-lg p-4 text-sm">
                        <div class="flex justify-between items-start gap-2 mb-2">
                            <span
                                class="font-semibold text-gray-900">{{ $note->date ? $note->date->format('Y-m-d') : '—' }}</span>
                            @can('edit receiving notes')
                                <a href="{{ route('receiving-notes.edit', $note) }}"
                                    class="text-indigo-600 hover:text-indigo-900 text-sm whitespace-nowrap">
                                    {{ __('messages.edit') }}
                                </a>
                            @endcan
                        </div>
                        <div class="space-y-1 text-gray-700 mb-3">
                            <p><span class="text-gray-500">{{ __('messages.container_no') }}:</span>
                                {{ $note->container_no ?? '—' }}</p>
                            <p><span class="text-gray-500">{{ __('messages.seal_no') }}:</span>
                                {{ $note->seal_no ?? '—' }}</p>
                            @if ($note->proformaInvoice)
                                <p><span class="text-gray-500">{{ __('messages.proforma_invoices') }}:</span>
                                    #{{ $note->proformaInvoice->id }}</p>
                            @endif
                        </div>
                        <div class="flex justify-end items-center space-x-3 pt-2 border-t">
                            <a href="{{ route('receiving-notes.show', $note) }}"
                                class="text-blue-600 hover:text-blue-900" title="{{ __('messages.view') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            @can('delete receiving notes')
                                <form action="{{ route('receiving-notes.destroy', $note) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                        title="{{ __('messages.delete') }}" onclick="return confirm('Are you sure?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                @endforeach
                <div class="pt-2">{{ $receivingNotes->links() }}</div>
            </div>

            <!-- DESKTOP : TABLE -->
            <div class="hidden lg:block bg-white shadow-md rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.date') }}</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.container_no') }}</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.seal_no') }}</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.proforma_invoices') }}</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($receivingNotes as $note)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ $note->date ? $note->date->format('Y-m-d') : '—' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $note->container_no ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $note->seal_no ?? '—' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if ($note->proformaInvoice)
                                            <a href="{{ route('proforma-invoices.show', $note->proformaInvoice) }}"
                                                class="text-blue-600 hover:underline">
                                                #{{ $note->proformaInvoice->id }}
                                            </a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end items-center space-x-3">
                                            <a href="{{ route('receiving-notes.show', $note) }}"
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
                                            @can('edit receiving notes')
                                                <a href="{{ route('receiving-notes.edit', $note) }}"
                                                    class="text-indigo-600 hover:text-indigo-900"
                                                    title="{{ __('messages.edit') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path
                                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                    </svg>
                                                </a>
                                            @endcan
                                            @can('delete receiving notes')
                                                <form action="{{ route('receiving-notes.destroy', $note) }}" method="POST"
                                                    class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                                        title="{{ __('messages.delete') }}"
                                                        onclick="return confirm('Are you sure?')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6 px-6 pb-6">{{ $receivingNotes->links() }}</div>
            </div>
        @endif
    </div>
</x-app-layout>
