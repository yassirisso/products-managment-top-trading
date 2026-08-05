<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>
        {{ config('app.name', 'Top Trading') }}
    </title>


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">

    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])


</head>



<body class="font-sans antialiased">


    <div class="min-h-screen flex bg-gray-950">


        <!-- LEFT SIDE BRANDING -->

        <div class="hidden lg:flex w-1/2 relative overflow-hidden">


            <!-- Background -->

            <div class="absolute inset-0 bg-gradient-to-br from-blue-900 via-gray-900 to-black"></div>



            <!-- Glow Effects -->

            <div class="absolute top-20 left-20 w-72 h-72 bg-blue-500 rounded-full opacity-20 blur-3xl"></div>

            <div class="absolute bottom-20 right-20 w-72 h-72 bg-purple-500 rounded-full opacity-20 blur-3xl"></div>



            <div class="relative z-10 flex flex-col justify-center px-16 text-white">


                <div class="mb-10">

                    <div class="w-20 h-20 rounded-2xl bg-white/10 backdrop-blur flex items-center justify-center mb-6">

                        <span class="text-4xl font-bold">
                            TT
                        </span>

                    </div>



                    <h1 class="text-5xl font-bold">

                        Top Trading

                    </h1>


                    <p class="mt-5 text-lg text-gray-300 leading-relaxed">

                        Smart management system for products,
                        suppliers, invoices and business operations.

                    </p>


                </div>




                <div class="grid grid-cols-2 gap-5">


                    <div class="bg-white/10 backdrop-blur rounded-2xl p-5">

                        <h3 class="text-2xl font-bold">
                            Inventory
                        </h3>

                        <p class="text-gray-300 text-sm mt-2">
                            Manage your products easily
                        </p>

                    </div>



                    <div class="bg-white/10 backdrop-blur rounded-2xl p-5">

                        <h3 class="text-2xl font-bold">
                            Finance
                        </h3>

                        <p class="text-gray-300 text-sm mt-2">
                            Track invoices and payments
                        </p>

                    </div>


                </div>



            </div>


        </div>





        <!-- RIGHT SIDE -->

        <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-10">


            <div class="w-full max-w-md">


                <div class="bg-white rounded-3xl shadow-2xl p-10">


                    {{ $slot }}


                </div>



                <p class="text-center text-gray-500 text-sm mt-6">

                    © {{ date('Y') }} Top Trading. All rights reserved.

                </p>



            </div>


        </div>



    </div>



</body>

</html>
