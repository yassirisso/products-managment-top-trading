<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl text-gray-800">
            {{ __('messages.edit_user') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-6">
        <div class="bg-white shadow-md rounded-lg p-6">
            <form method="POST" action="{{ route('users.update', $user->id) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.name') }}</label>
                        <input type="text" name="name" value="{{ $user->name }}"
                            class="border rounded-lg w-full p-2">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.email') }}</label>
                        <input type="email" name="email" value="{{ $user->email }}"
                            class="border rounded-lg w-full p-2">
                    </div>
                </div>

                <!-- Roles -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">{{ __('messages.role') }}</label>
                    <div class="flex gap-4 flex-wrap">
                        @foreach ($roles as $role)
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="role"
                                    class="role-radio rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    value="{{ $role->name }}" data-permissions='@json($role->permissions->pluck('name'))'
                                    {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                                <span>{{ $role->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Account Status -->
                <div class="mb-6">
                    <label
                        class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.account_status') }}</label>
                    <select name="is_active" class="w-full md:w-1/2 border rounded-lg px-3 py-2">
                        <option value="1" {{ $user->is_active ? 'selected' : '' }}>{{ __('messages.active') }}
                        </option>
                        <option value="0" {{ !$user->is_active ? 'selected' : '' }}>{{ __('messages.inactive') }}
                        </option>
                    </select>
                </div>

                <!-- Permissions -->
                <div class="mb-6 border-t pt-6">
                    <label class="block text-gray-700 font-bold mb-2">{{ __('messages.permissions') }}</label>
                    @php $rolePermissions = $user->getPermissionsViaRoles()->pluck('name'); @endphp
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-2 bg-gray-50 p-4 rounded-lg border">
                        @foreach ($permissions as $permission)
                            <label class="flex items-start gap-2 text-sm">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                    class="mt-1 permission-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    {{ $rolePermissions->contains($permission->name) ? 'checked' : '' }}>
                                <span class="break-words">{{ __('messages.' . $permission->name) }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="text-sm text-red-600 mt-3">
                        {{ __('messages.changing_permissions_warning') }}
                        "<strong>{{ request()->old('role', $user->roles->first()?->name) }}</strong>"
                    </p>
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('users.index') }}"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800">{{ __('messages.cancel') }}</a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white px-6 py-2 rounded">
                        {{ __('messages.save_changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roles = document.querySelectorAll('.role-radio');
            const permissions = document.querySelectorAll('.permission-checkbox');

            roles.forEach(function(role) {
                role.addEventListener('change', function() {
                    let rolePermissions = JSON.parse(this.dataset.permissions);
                    permissions.forEach(function(permission) {
                        permission.checked = rolePermissions.includes(permission.value);
                    });
                });
            });
        });
    </script>
</x-app-layout>
