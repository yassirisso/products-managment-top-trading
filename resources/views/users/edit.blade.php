<x-app-layout>

<x-slot name="header">

<h2 class="font-semibold text-xl text-gray-800">
    Edit User
</h2>

</x-slot>


<div class="container mx-auto px-4 py-6">


<div class="bg-white shadow-md rounded-lg p-6">


<form method="POST"
      action="{{ route('users.update',$user->id) }}">


@csrf

@method('PUT')



<!-- Name -->

<div class="mb-4">

<label class="block font-bold mb-2">
Name
</label>

<input type="text"
name="name"
value="{{ $user->name }}"
class="border rounded w-full p-2">

</div>



<!-- Email -->

<div class="mb-4">

<label class="block font-bold mb-2">
Email
</label>

<input type="email"
name="email"
value="{{ $user->email }}"
class="border rounded w-full p-2">

</div>


<!-- Roles -->

<div class="mb-6">

    <label class="block text-gray-700 font-bold mb-2">
        Role
    </label>

    @foreach($roles as $role)

        <label class="block">

            <input 
                type="radio"
                name="role"
                class="role-radio"
                value="{{ $role->name }}"
                data-permissions='@json($role->permissions->pluck("name"))'
                {{ $user->hasRole($role->name) ? 'checked' : '' }}
            >

            {{ $role->name }}

        </label>

    @endforeach

</div>



<!-- Permissions -->

<div class="mb-6">

    <label class="block text-gray-700 font-bold mb-2">
        Permissions
    </label>


    @foreach($permissions as $permission)

        <label class="block">

            <input 
                type="checkbox"
                class="permission-checkbox"
                name="permissions[]"
                value="{{ $permission->name }}"
                {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}
            >

            {{ $permission->name }}

        </label>

    @endforeach


</div>



<button
class="bg-blue-500 text-white px-4 py-2 rounded">

Save Changes

</button>



</form>


</div>


</div>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const roles = document.querySelectorAll('.role-radio');
    const permissions = document.querySelectorAll('.permission-checkbox');


    roles.forEach(function(role){

        role.addEventListener('change', function(){

            let rolePermissions = JSON.parse(this.dataset.permissions);


            permissions.forEach(function(permission){

                permission.checked = rolePermissions.includes(permission.value);

            });

        });

    });

});

</script>


</x-app-layout>