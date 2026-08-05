<x-guest-layout>


    <!-- Session Status -->

    <x-auth-session-status class="mb-6" :status="session('status')" />



    <div class="mb-8">


        <h2 class="text-3xl font-bold text-gray-900">

            Welcome Back 👋

        </h2>


        <p class="text-gray-500 mt-2">

            Login to manage your business dashboard

        </p>


    </div>




    <form method="POST" action="{{ route('login') }}">

        @csrf



        <!-- Email -->

        <div>


            <x-input-label for="email" :value="__('Email')" class="font-medium" />



            <x-text-input id="email"
                class="block mt-2 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                placeholder="Enter your email" />



            <x-input-error :messages="$errors->get('email')" class="mt-2" />


        </div>





        <!-- Password -->

        <div class="mt-5">


            <x-input-label for="password" :value="__('Password')" class="font-medium" />



            <x-text-input id="password"
                class="block mt-2 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                type="password" name="password" required autocomplete="current-password"
                placeholder="Enter your password" />



            <x-input-error :messages="$errors->get('password')" class="mt-2" />


        </div>





        <!-- Remember -->

        <div class="mt-5">


            <label class="flex items-center">


                <input id="remember_me" type="checkbox" name="remember"
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">


                <span class="ml-2 text-sm text-gray-600">

                    Remember me

                </span>


            </label>


        </div>





        <!-- Button -->

        <button type="submit"
            class="mt-8 w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl shadow-lg transition duration-200">

            Login

        </button>





        @if (Route::has('password.request'))
            <div class="text-center mt-5">


                <a href="{{ route('password.request') }}"
                    class="text-sm text-blue-600 hover:text-blue-800 hover:underline">

                    Forgot your password?

                </a>


            </div>
        @endif



    </form>



</x-guest-layout>
