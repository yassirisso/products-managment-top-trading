<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 bg-gray-800">
                <div class="flex items-center h-16 px-4 bg-gray-900">
                    <span class="text-white font-semibold text-lg">TOP TRADING</span>
                </div>
                <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto">
                    <nav class="flex-1 px-2 space-y-1">
                        <!-- Dashboard Link -->
                        <a href="{{ route('dashboard') }}" class="@if(request()->routeIs('dashboard')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <svg class="mr-3 h-6 w-6 @if(request()->routeIs('dashboard')) text-gray-300 @else text-gray-400 group-hover:text-gray-300 @endif" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>

                        <!-- Products Link -->
                        <a href="{{ route('products.index') }}" class="@if(request()->routeIs('products*')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <svg class="mr-3 h-6 w-6 @if(request()->routeIs('products*')) text-gray-300 @else text-gray-400 group-hover:text-gray-300 @endif" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Products
                        </a>

                        <!-- Clients Link -->
                        <a href="{{ route('clients.index') }}" class="@if(request()->routeIs('clients*')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <svg class="mr-3 h-6 w-6 @if(request()->routeIs('clients*')) text-gray-300 @else text-gray-400 group-hover:text-gray-300 @endif" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Clients
                        </a>

                        <!-- Suppliers Link -->
                        <a href="{{ route('suppliers.index') }}" class="@if(request()->routeIs('suppliers*')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <svg class="mr-3 h-6 w-6 @if(request()->routeIs('suppliers*')) text-gray-300 @else text-gray-400 group-hover:text-gray-300 @endif" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Suppliers
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Mobile header -->
            <div class="md:hidden bg-gray-800 text-white p-4 flex justify-between items-center">
                <span class="font-semibold text-lg">TOP TRADING</span>
                <button id="mobile-menu-button" class="text-white focus:outline-none">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Mobile sidebar (hidden by default) -->
            <div id="mobile-menu" class="md:hidden hidden bg-gray-800 pb-3">
                <nav class="px-2 space-y-1">
                    <a href="{{ route('dashboard') }}" class="@if(request()->routeIs('dashboard')) bg-gray-900 text-white @else text-gray-300 hover:bg-gray-700 hover:text-white @endif group flex items-center px-2 py-2 text-base font-medium rounded-md">
                        <svg class="mr-4 h-6 w-6 @if(request()->routeIs('dashboard')) text-gray-300 @else text-gray-400 group-hover:text-gray-300 @endif" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                    <!-- Repeat for other menu items -->
                </nav>
            </div>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile menu script -->
    <script>
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>
</html>
