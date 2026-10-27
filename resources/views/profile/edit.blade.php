<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
            {{ __('messages.my_profile') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8">

        <!-- HERO -->
        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 p-6 md:p-8 mb-8 shadow-lg">
            <div class="absolute -top-16 -right-16 w-56 h-56 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-20 -left-20 w-64 h-64 rounded-full bg-white/5"></div>

            <div class="relative flex flex-col sm:flex-row sm:items-center gap-5">
                <div
                    class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-white text-3xl font-bold shrink-0 ring-4 ring-white/30">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div class="min-w-0 text-white">
                    <p class="text-xs uppercase tracking-widest text-white/70 mb-1">{{ __('messages.account') }}</p>
                    <h1 class="text-2xl md:text-3xl font-bold break-words">{{ auth()->user()->name }}</h1>
                    <p class="text-white/80 text-sm break-words">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>

        <!-- PROFILE + PASSWORD (side by side) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- PROFILE INFO -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                <div class="flex items-start gap-3 mb-6">
                    <div
                        class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ __('messages.update_profile_info') }}</h2>
                        <p class="text-sm text-gray-500">{{ __('messages.update_profile_text') }}</p>
                    </div>
                </div>

                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- PASSWORD -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                <div class="flex items-start gap-3 mb-6">
                    <div
                        class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ __('messages.update_password') }}</h2>
                        <p class="text-sm text-gray-500">{{ __('messages.update_password_text') }}</p>
                    </div>
                </div>

                @include('profile.partials.update-password-form')
            </div>

        </div>
    </div>
</x-app-layout>
