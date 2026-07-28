<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Users Management
        </h2>

    </x-slot>


    <div class="container mx-auto px-4 py-6">

        <!-- TOP -->
        <div class="flex justify-between items-center mb-6">

            <h1 class="text-2xl font-bold">

                Users Management

            </h1>

            <a href="{{ route('users.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">

                Add User

            </a>

        </div>

        <!-- SUCCESS -->
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4">

                {{ session('success') }}

            </div>
        @endif
        <div class="bg-white shadow-md rounded-lg overflow-hidden">


            <table class="min-w-full divide-y divide-gray-200">


                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Name
                        </th>


                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Email
                        </th>


                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Role
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Status
                        </th>


                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                            Action
                        </th>

                    </tr>

                </thead>



                <tbody class="bg-white divide-y divide-gray-200">


                    @foreach ($users as $user)
                        <tr>


                            <td class="px-6 py-4">
                                {{ $user->name }}
                            </td>


                            <td class="px-6 py-4">
                                {{ $user->email }}
                            </td>


                            <td class="px-6 py-4">

                                @foreach ($user->roles as $role)
                                    <span class="px-2 py-1 bg-gray-200 rounded">
                                        {{ $role->name }}
                                    </span>
                                @endforeach

                            </td>

                            <td>

                                @if ($user->is_active)
                                    <span class="text-green-600 font-semibold">
                                        Active
                                    </span>
                                @else
                                    <span class="text-red-600 font-semibold">
                                        Inactive
                                    </span>
                                @endif

                            </td>


                            <td class="px-6 py-4 text-right">

                                <a href="{{ route('users.edit', $user->id) }}" class="text-blue-600 hover:underline">

                                    Edit

                                </a>

                            </td>


                        </tr>
                    @endforeach


                </tbody>


            </table>


        </div>


    </div>


</x-app-layout>
