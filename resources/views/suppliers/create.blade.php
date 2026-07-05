<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

            Add New Supplier

        </h2>

    </x-slot>

    <div class="container mx-auto px-4 py-6">

        <div class="flex items-center justify-between mb-6">

            <h1 class="text-2xl font-bold text-gray-800">

                Add New Supplier

            </h1>

            <a href="{{ route('suppliers.index') }}"
               class="text-gray-600 hover:text-gray-800 flex items-center">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5 mr-1"
                     viewBox="0 0 20 20"
                     fill="currentColor">

                    <path fill-rule="evenodd"
                          d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                          clip-rule="evenodd" />

                </svg>

                Back to Suppliers

            </a>

        </div>

        <div class="bg-white shadow-md rounded-lg p-6">

            <form action="{{ route('suppliers.store') }}"
                  method="POST">

                @csrf

                <div class="mb-6">

                    <div class="flex gap-4">

                        <!-- NAME -->
                        <div class="w-1/3">

                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Supplier Information
                            </label>

                            <input type="text"
                                name="name"
                                id="name"
                                value="{{ old('name') }}"
                                required
                                autofocus
                                placeholder="Supplier name"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">

                            @error('name')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror

                        </div>

                        <!-- EMAIL -->
                        <div class="w-1/3">

                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Supplier Email
                            </label>

                            <input type="email"
                                name="email"
                                id="email"
                                value="{{ old('email') }}"
                                placeholder="Supplier email"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">

                            @error('email')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror

                        </div>

                        <!-- PHONE -->
                        <div class="w-1/3">

                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Supplier Phone
                            </label>

                            <input type="text"
                                name="phone"
                                id="phone"
                                value="{{ old('phone') }}"
                                placeholder="Supplier phone"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">

                            @error('phone')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror

                        </div>

                    </div>
                </div>

                <!-- BUTTONS -->
                <div class="flex items-center justify-end space-x-4">

                    <a href="{{ route('suppliers.index') }}"
                       class="text-gray-600 hover:text-gray-800">

                        Cancel

                    </a>

                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">

                        Save Supplier

                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>