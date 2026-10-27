<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Top Trading</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

</head>

<body class="bg-gray-100 font-sans antialiased">

    <div class="flex h-screen">

        <!-- MOBILE BACKDROP -->
        <div id="mobile-menu-backdrop" class="hidden md:hidden fixed inset-0 bg-black bg-opacity-40 z-30"></div>

        <!-- Sidebar -->
        <div id="mobile-menu"
            class="hidden md:flex md:flex-shrink-0
                    fixed md:static inset-y-0 left-0 z-40
                    w-64 md:w-auto">

            <div class="flex flex-col w-64 bg-gray-800">

                <div class="flex items-center justify-between h-16 px-4 bg-gray-900">

                    <span class="text-white font-semibold text-lg">

                        TOP TRADING

                    </span>

                    <!-- CLOSE BUTTON (mobile only) -->
                    <button type="button" id="mobile-menu-close" class="md:hidden text-gray-300 hover:text-white">

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />

                        </svg>

                    </button>

                </div>

                <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto">

                    <nav class="flex-1 px-2 space-y-1">

                        <!-- Dashboard -->
                        @can('view dashboard')
                            <a href="{{ route('dashboard') }}"
                                class="@if (request()->routeIs('dashboard')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                {{ __('messages.dashboard') }}
                            </a>
                        @endcan

                        <!-- Products -->
                        @can('view products')
                            <a href="{{ route('products.index') }}"
                                class="@if (request()->routeIs('products*')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                {{ __('messages.products') }}
                            </a>
                        @endcan

                        <!-- Clients -->
                        @can('view clients')
                            <a href="{{ route('clients.index') }}"
                                class="@if (request()->routeIs('clients*')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                {{ __('messages.clients') }}
                            </a>
                        @endcan

                        <!-- Suppliers -->
                        @can('view suppliers')
                            <a href="{{ route('suppliers.index') }}"
                                class="@if (request()->routeIs('suppliers*')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                {{ __('messages.suppliers') }}
                            </a>
                        @endcan

                        <!-- Packing Lists -->
                        @can('view packing lists')
                            <a href="{{ route('packing-lists.index') }}"
                                class="@if (request()->routeIs('packing-lists*')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                {{ __('messages.packing_lists') }}
                            </a>
                        @endcan

                        <!-- Proforma Invoices -->
                        @can('view proforma invoices')
                            <a href="{{ route('proforma-invoices.index') }}"
                                class="@if (request()->routeIs('proforma-invoices*')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                {{ __('messages.proforma_invoices') }}
                            </a>
                        @endcan

                        <!-- Commercial Invoices -->
                        @can('view commercial invoices')
                            <a href="{{ route('commercial-invoices.index') }}"
                                class="@if (request()->routeIs('commercial-invoices*')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                {{ __('messages.commercial_invoices') }}
                            </a>
                        @endcan

                        <!-- Bank Accounts -->
                        @can('view bank accounts')
                            <a href="{{ route('bank-accounts.index') }}"
                                class="@if (request()->routeIs('bank-accounts*')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                {{ __('messages.bank_accounts') }}
                            </a>
                        @endcan

                        <!-- Shipping Companies -->
                        @can('view shipping companies')
                            <a href="{{ route('shipping-companies.index') }}"
                                class="@if (request()->routeIs('shipping-companies*')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                {{ __('messages.shipping_companies') }}
                            </a>
                        @endcan

                        <!-- Warehouse Fees -->
                        <a href="{{ route('warehouse-fees.index') }}"
                            class="@if (request()->routeIs('warehouse-fees*')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            {{ __('messages.warehouse_fees') }}
                        </a>

                        <!-- Shipping Marks -->
                        <a href="{{ route('shipping-marks.index') }}"
                            class="@if (request()->routeIs('shipping-marks*')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            {{ __('messages.shipping_marks') }}
                        </a>

                        <!-- Users -->
                        @can('view users')
                            <a href="{{ route('users.index') }}"
                                class="@if (request()->routeIs('users*')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                                {{ __('messages.users') }}
                            </a>
                        @endcan

                    </nav>

                </div>

            </div>

        </div>


        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">

            <!-- Top Bar -->
            <div class="bg-white shadow px-4 md:px-6 py-4 flex justify-between items-center">

                <div class="flex items-center gap-3 min-w-0">

                    <!-- BURGER (mobile / tablet only) -->
                    <button type="button" id="mobile-menu-button"
                        class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none shrink-0">

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />

                        </svg>

                    </button>

                    <div class="min-w-0">

                        @isset($header)
                            {{ $header }}
                        @endisset

                    </div>

                </div>

                <div class="flex items-center space-x-2 md:space-x-4 shrink-0">

                    <!-- LANGUAGE SWITCHER -->
                    <div class="flex items-center bg-gray-100 rounded-lg p-1 text-xs font-semibold">
                        <a href="{{ route('lang.switch', 'en') }}"
                            class="px-2 py-1 rounded {{ app()->getLocale() == 'en' ? 'bg-white shadow text-blue-600' : 'text-gray-500 hover:text-gray-800' }}">
                            EN
                        </a>
                        <a href="{{ route('lang.switch', 'zh_CN') }}"
                            class="px-2 py-1 rounded {{ app()->getLocale() == 'zh_CN' ? 'bg-white shadow text-blue-600' : 'text-gray-500 hover:text-gray-800' }}">
                            中文
                        </a>
                    </div>

                    <a href="{{ route('profile.edit') }}" class="text-gray-700 hover:text-gray-700 hidden sm:inline"
                        title="My Profile">

                        {{ auth()->user()->name }}

                    </a>

                    <form method="POST" action="{{ route('logout') }}">

                        @csrf

                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">

                            {{ __('messages.logout') }}

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
        (function() {

            const button = document.getElementById('mobile-menu-button');
            const closeBtn = document.getElementById('mobile-menu-close');
            const menu = document.getElementById('mobile-menu');
            const backdrop = document.getElementById('mobile-menu-backdrop');

            if (!button || !menu || !backdrop) return;

            function openMenu() {
                menu.classList.remove('hidden');
                menu.classList.add('flex');
                backdrop.classList.remove('hidden');
            }

            function closeMenu() {
                menu.classList.add('hidden');
                menu.classList.remove('flex');
                backdrop.classList.add('hidden');
            }

            button.addEventListener('click', openMenu);

            if (closeBtn) closeBtn.addEventListener('click', closeMenu);

            backdrop.addEventListener('click', closeMenu);

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeMenu();
            });

            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) closeMenu();
            });

        })();
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</body>

</html>
