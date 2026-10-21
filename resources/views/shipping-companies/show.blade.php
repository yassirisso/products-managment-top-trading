<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

            {{ $shippingCompany->name }}

        </h2>

    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">

            <div>

                <h1 class="text-2xl font-bold text-gray-800">

                    {{ $shippingCompany->name }}

                </h1>

                <p class="text-gray-600">

                    Shipping Company Details

                </p>

            </div>

            <div class="flex space-x-2">

                <a href="{{ route('shipping-companies.edit', $shippingCompany) }}"
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded flex items-center"
                   title="Edit Shipping Company">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         viewBox="0 0 20 20"
                         fill="currentColor">

                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />

                    </svg>

                </a>

                <a href="{{ route('shipping-companies.index') }}"
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-3 rounded flex items-center"
                   title="Back to Shipping Companies">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         viewBox="0 0 20 20"
                         fill="currentColor">

                        <path fill-rule="evenodd"
                              d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                              clip-rule="evenodd" />

                    </svg>

                </a>

            </div>

        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">

                <p>{{ session('success') }}</p>

            </div>
        @endif

        <!-- COMPANY INFO -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">

            <h2 class="text-xl font-semibold mb-4">
                Company Information
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div>

                    <p class="text-gray-600">
                        Name:
                    </p>

                    <p class="font-medium">
                        {{ $shippingCompany->name }}
                    </p>

                </div>

                <div>

                    <p class="text-gray-600">
                        Telephone:
                    </p>

                    <p class="font-medium">
                        {{ $shippingCompany->tel ?? '—' }}
                    </p>

                </div>

                <div>

                    <p class="text-gray-600">
                        Email:
                    </p>

                    <p class="font-medium">
                        {{ $shippingCompany->email ?? '—' }}
                    </p>

                </div>

            </div>
        </div>

        <!-- CONTACTS -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">

            <div class="flex justify-between items-center mb-4">

                <h2 class="text-xl font-semibold">

                    Contact Persons

                </h2>

                <button onclick="document.getElementById('addContactModal').classList.remove('hidden')"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex items-center">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5 mr-1"
                         viewBox="0 0 20 20"
                         fill="currentColor">

                        <path fill-rule="evenodd"
                              d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                              clip-rule="evenodd" />

                    </svg>

                    Add Contact

                </button>

            </div>

            @if($shippingCompany->contacts->isEmpty())

                <p class="text-gray-500">

                    No contacts associated with this shipping company.

                </p>

            @else

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Position
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Phone
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>

                            </tr>

                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">

                            @foreach($shippingCompany->contacts as $contact)

                                <tr class="hover:bg-gray-50">

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $contact->name }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $contact->position ?? '—' }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $contact->phone ?? '—' }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $contact->email ?? '—' }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">

                                        <form action="{{ route('shipping-companies.contacts.destroy', ['shippingCompany' => $shippingCompany, 'contact' => $contact]) }}"
                                              method="POST"
                                              class="inline">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="text-red-500 hover:text-red-700"
                                                    title="Remove Contact"
                                                    onclick="return confirm('Are you sure you want to remove this contact?')">

                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-5 w-5"
                                                     viewBox="0 0 20 20"
                                                     fill="currentColor">

                                                    <path fill-rule="evenodd"
                                                          d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                          clip-rule="evenodd" />

                                                </svg>

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </div>

        <!-- SHIPPING FEES HISTORY -->
        <div class="bg-white shadow-md rounded-lg p-6">

            <div class="flex justify-between items-center mb-4">

                <h2 class="text-xl font-semibold">

                    Shipping Fees History

                </h2>

                <button onclick="document.getElementById('addFeeModal').classList.remove('hidden')"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex items-center">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5 mr-1"
                         viewBox="0 0 20 20"
                         fill="currentColor">

                        <path fill-rule="evenodd"
                              d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                              clip-rule="evenodd" />

                    </svg>

                    Add Fee

                </button>

            </div>

            @if($shippingCompany->fees->isEmpty())

                <p class="text-gray-500">

                    No shipping fees recorded for this company.

                </p>

            @else

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Commercial Invoice
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Client
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Note
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>

                            </tr>

                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">

                            @foreach($shippingCompany->fees as $fee)

                                <tr class="hover:bg-gray-50">

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $fee->date->format('Y-m-d') }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">

                                        @if($fee->commercialInvoice)

                                            <a href="{{ route('commercial-invoices.show', $fee->commercialInvoice) }}"
                                               class="text-blue-600 hover:underline">

                                                #{{ $fee->commercialInvoice->invoice_no ?? $fee->commercialInvoice->id }}

                                            </a>

                                        @else

                                            —

                                        @endif

                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $fee->commercialInvoice?->client?->name ?? '—' }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap font-medium">
                                        {{ number_format($fee->amount, 2) }} {{ $fee->currency }}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $fee->note ?? '—' }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">

                                        <form action="{{ route('shipping-companies.fees.destroy', ['shippingCompany' => $shippingCompany, 'fee' => $fee]) }}"
                                              method="POST"
                                              class="inline">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="text-red-500 hover:text-red-700"
                                                    title="Remove Fee"
                                                    onclick="return confirm('Are you sure you want to remove this fee?')">

                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-5 w-5"
                                                     viewBox="0 0 20 20"
                                                     fill="currentColor">

                                                    <path fill-rule="evenodd"
                                                          d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                          clip-rule="evenodd" />

                                                </svg>

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </div>

        <!-- ADD CONTACT MODAL -->
        <div id="addContactModal"
             class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">

            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">

                <div class="flex justify-between items-center mb-4">

                    <h3 class="text-lg font-semibold">

                        Add Contact Person

                    </h3>

                    <button onclick="document.getElementById('addContactModal').classList.add('hidden')"
                            class="text-gray-500 hover:text-gray-700">

                        &times;

                    </button>

                </div>

                <form action="{{ route('shipping-companies.contacts.store', $shippingCompany) }}"
                      method="POST">

                    @csrf

                    <div class="mb-4">

                        <label for="contact_name"
                               class="block text-gray-700 text-sm font-bold mb-2">

                            Name *

                        </label>

                        <input type="text"
                               name="name"
                               id="contact_name"
                               required
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">

                    </div>

                    <div class="mb-4">

                        <label for="contact_position"
                               class="block text-gray-700 text-sm font-bold mb-2">

                            Position

                        </label>

                        <input type="text"
                               name="position"
                               id="contact_position"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">

                    </div>

                    <div class="mb-4">

                        <label for="contact_phone"
                               class="block text-gray-700 text-sm font-bold mb-2">

                            Phone

                        </label>

                        <input type="text"
                               name="phone"
                               id="contact_phone"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">

                    </div>

                    <div class="mb-4">

                        <label for="contact_email"
                               class="block text-gray-700 text-sm font-bold mb-2">

                            Email

                        </label>

                        <input type="email"
                               name="email"
                               id="contact_email"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">

                    </div>

                    <div class="flex justify-end">

                        <button type="button"
                                onclick="document.getElementById('addContactModal').classList.add('hidden')"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">

                            Cancel

                        </button>

                        <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">

                            Add Contact

                        </button>

                    </div>

                </form>

            </div>

        </div>

        <!-- ADD FEE MODAL -->
        <div id="addFeeModal"
             class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">

            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">

                <div class="flex justify-between items-center mb-4">

                    <h3 class="text-lg font-semibold">

                        Add Shipping Fee

                    </h3>

                    <button onclick="document.getElementById('addFeeModal').classList.add('hidden')"
                            class="text-gray-500 hover:text-gray-700">

                        &times;

                    </button>

                </div>

                <form action="{{ route('shipping-companies.fees.store', $shippingCompany) }}"
                      method="POST">

                    @csrf

                    <div class="mb-4">

                        <label for="commercial_invoice_id"
                               class="block text-gray-700 text-sm font-bold mb-2">

                            Commercial Invoice *

                        </label>

                        <select name="commercial_invoice_id"
                                id="commercial_invoice_id"
                                required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">

                            <option value="">
                                Select Commercial Invoice
                            </option>

                            @foreach($commercialInvoices as $invoice)

                                <option value="{{ $invoice->id }}">

                                    #{{ $invoice->invoice_no ?? $invoice->id }}
                                    — {{ $invoice->client?->name }}
                                    ({{ $invoice->date }})

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="mb-4 flex gap-2">

                        <div class="flex-1">

                            <label for="amount"
                                   class="block text-gray-700 text-sm font-bold mb-2">

                                Amount *

                            </label>

                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="amount"
                                   id="amount"
                                   required
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">

                        </div>

                        <div class="w-32">

                            <label for="currency"
                                   class="block text-gray-700 text-sm font-bold mb-2">

                                Currency *

                            </label>

                            <select name="currency"
                                    id="currency"
                                    required
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">

                                <option value="RMB">RMB</option>
                                <option value="USD">USD</option>

                            </select>

                        </div>

                    </div>

                    <div class="mb-4">

                        <label for="date"
                               class="block text-gray-700 text-sm font-bold mb-2">

                            Date *

                        </label>

                        <input type="date"
                               name="date"
                               id="date"
                               required
                               value="{{ date('Y-m-d') }}"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">

                    </div>

                    <div class="mb-4">

                        <label for="note"
                               class="block text-gray-700 text-sm font-bold mb-2">

                            Note

                        </label>

                        <textarea name="note"
                                  id="note"
                                  rows="3"
                                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>

                    </div>

                    <div class="flex justify-end">

                        <button type="button"
                                onclick="document.getElementById('addFeeModal').classList.add('hidden')"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">

                            Cancel

                        </button>

                        <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">

                            Add Fee

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <script>

        window.onclick = function(event)
        {
            ['addContactModal', 'addFeeModal'].forEach(function(id) {

                const modal = document.getElementById(id);

                if (event.target === modal) {

                    modal.classList.add('hidden');
                }
            });
        }

    </script>

</x-app-layout>