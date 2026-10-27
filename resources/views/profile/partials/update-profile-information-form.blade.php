<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('messages.update_profile_info') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('messages.update_profile_text') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- NAME -->
        <div>
            <x-input-label for="name" :value="__('messages.name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- EMAIL -->
        <div>
            <x-input-label for="email" :value="__('messages.email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('messages.your_email_unverified') }}
                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('messages.click_resend_verification') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('messages.verification_link_sent') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- COMPANY NAME -->
        <div>
            <x-input-label for="company_name" :value="__('messages.company_name')" />
            <x-text-input id="company_name" name="company_name" type="text" class="mt-1 block w-full"
                :value="old('company_name', $user->company_name)" />
            <x-input-error class="mt-2" :messages="$errors->get('company_name')" />
        </div>

        <!-- COMPANY ADDRESS -->
        <div>
            <x-input-label for="company_address" :value="__('messages.company_address')" />
            <textarea id="company_address" name="company_address" rows="3"
                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">{{ old('company_address', $user->company_address) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('company_address')" />
        </div>

        <!-- COMPANY PHONE -->
        <div>
            <x-input-label for="company_phone" :value="__('messages.company_phone')" />
            <x-text-input id="company_phone" name="company_phone" type="text" class="mt-1 block w-full"
                :value="old('company_phone', $user->company_phone)" />
            <x-input-error class="mt-2" :messages="$errors->get('company_phone')" />
        </div>

        <!-- COMPANY FAX -->
        <div>
            <x-input-label for="company_fax" :value="__('messages.company_fax')" />
            <x-text-input id="company_fax" name="company_fax" type="text" class="mt-1 block w-full"
                :value="old('company_fax', $user->company_fax)" />
            <x-input-error class="mt-2" :messages="$errors->get('company_fax')" />
        </div>

        <!-- SAVE BUTTON -->
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('messages.save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('messages.saved') }}</p>
            @endif
        </div>
    </form>
</section>
