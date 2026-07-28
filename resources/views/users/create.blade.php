<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800">
            Add New User
        </h2>

    </x-slot>


    <div class="py-6">

        <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">


            <form action="{{ route('users.store') }}" method="POST">

                @csrf


                <div class="mb-4">

                    <label>Name</label>

                    <input type="text" name="name" class="w-full border rounded" required>

                </div>



                <div class="mb-4">

                    <label>Email</label>

                    <input type="email" name="email" class="w-full border rounded" required>

                </div>



                <div class="mb-4">

                    <label>Password</label>

                    <input type="password" name="password" class="w-full border rounded" required>

                </div>

                <div class="mb-4">

                    <label>Role</label>

                    <select name="role" class="w-full border rounded">

                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}">
                                {{ $role->name }}
                            </option>
                        @endforeach

                    </select>

                </div>

                <div class="mb-4">

                    <label class="block">
                        Account Status
                    </label>


                    <select name="is_active" class="w-full border rounded">


                        <option value="1">
                            Active
                        </option>


                        <option value="0">
                            Inactive
                        </option>


                    </select>

                </div>



                <button class="bg-green-600 text-white px-6 py-2 rounded">

                    Save User

                </button>


            </form>


        </div>

    </div>

</x-app-layout>
