@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Card 1 -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-2">Total Products</h2>
                <p class="text-3xl font-bold text-blue-600">{{ App\Models\Product::count() }}</p>
            </div>

            <!-- Card 2 -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-2">Total Clients</h2>
                <p class="text-3xl font-bold text-green-600">{{ App\Models\Client::count() }}</p>
            </div>

            <!-- Card 3 -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-2">Total Suppliers</h2>
                <p class="text-3xl font-bold text-purple-600">{{ App\Models\Supplier::count() }}</p>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="mt-8 bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Recent Activity</h2>
            <div class="space-y-4">
                <!-- Example activity item -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">New product added</p>
                        <p class="text-sm text-gray-500">3 minutes ago</p>
                    </div>
                </div>
                <!-- Add more activity items as needed -->
            </div>
        </div>
    </div>
@endsection
