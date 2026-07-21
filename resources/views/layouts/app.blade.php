<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
          rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"
            defer></script>

</head>

<body class="bg-gray-100 font-sans antialiased">

    <div class="flex h-screen">

        <!-- Sidebar -->
        <div class="hidden md:flex md:flex-shrink-0">

            <div class="flex flex-col w-64 bg-gray-800">

                <div class="flex items-center justify-between h-16 px-4 bg-gray-900">

                    <span class="text-white font-semibold text-lg">

                        TOP TRADING

                    </span>

                </div>

                <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto">

                    <nav class="flex-1 px-2 space-y-1">

                        <!-- Dashboard -->
                        <a href="{{ route('dashboard') }}"
                           class="@if(request()->routeIs('dashboard')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">

                            Dashboard

                        </a>

                        <!-- Products -->
                        <a href="{{ route('products.index') }}"
                           class="@if(request()->routeIs('products*')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">

                            Products

                        </a>

                        <!-- Clients -->
                        <a href="{{ route('clients.index') }}"
                           class="@if(request()->routeIs('clients*')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">

                            Clients

                        </a>

                        <!-- Suppliers -->
                        <a href="{{ route('suppliers.index') }}"
                           class="@if(request()->routeIs('suppliers*')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">

                            Suppliers

                        </a>

                        <!-- Packing Lists -->
                        <a href="{{ route('packing-lists.index') }}"
                           class="@if(request()->routeIs('packing-lists*')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">

                            Packing Lists

                        </a>

                        <!-- PROFORMA INVOICE -->
                        <a href="{{ route('proforma-invoices.index') }}"
                           class="@if(request()->routeIs('proforma-invoices*')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">

                            Proforma Invoices

                        </a>

                        <!-- COMMERCIAL INVOICE -->
                        <a href="{{ route('commercial-invoices.index') }}"
                           class="@if(request()->routeIs('commercial-invoices*')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">

                            Commercial Invoices

                        </a>

                        <!-- Bank Accounts -->
                        <a href="{{ route('bank-accounts.index') }}"
                           class="@if(request()->routeIs('bank-accounts*')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">

                            Bank Accounts

                        </a>

                        <!-- Users -->
                        <a href="{{ route('users.index') }}"
                        class="@if(request()->routeIs('users*')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">

                            Users

                        </a>

                        </a>

                    </nav>

                </div>

            </div>

        </div>


        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">

            <!-- Top Bar -->
            <div class="bg-white shadow px-6 py-4 flex justify-between items-center">

                <div>

                    @isset($header)

                        {{ $header }}

                    @endisset

                </div>

                <div class="flex items-center space-x-4">

                    <span class="text-gray-700">

                        {{ auth()->user()->name }}

                    </span>

                    <form method="POST"
                          action="{{ route('logout') }}">

                        @csrf

                        <button type="submit"
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">

                            Logout

                        </button>

                    </form>

                </div>

            </div>


            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4">

                {{ $slot }}

            </main>

        </div>

    </div>


    <!-- Mobile menu script -->
    <script>

        document.getElementById('mobile-menu-button')?.addEventListener('click', function () {

            const menu = document.getElementById('mobile-menu');

            menu.classList.toggle('hidden');

        });

    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</body>

</html>