<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Users Management
        </h2>

    </x-slot>


    <div class="container mx-auto px-4 py-6">


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


                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                            Action
                        </th>

                    </tr>

                </thead>



                <tbody class="bg-white divide-y divide-gray-200">


                    @foreach($users as $user)

                    <tr>


                        <td class="px-6 py-4">
                            {{ $user->name }}
                        </td>


                        <td class="px-6 py-4">
                            {{ $user->email }}
                        </td>


                        <td class="px-6 py-4">

                            @foreach($user->roles as $role)

                                <span class="px-2 py-1 bg-gray-200 rounded">
                                    {{ $role->name }}
                                </span>

                            @endforeach

                        </td>


                        <td class="px-6 py-4 text-right">

                            <a href="{{ route('users.edit',$user->id) }}"
                               class="text-blue-600 hover:underline">

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