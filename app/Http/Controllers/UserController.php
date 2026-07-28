<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
        $users = User::with('roles')->latest()->get();

        return view('users.index', compact('users'));
    }


    public function create()
    {

        $roles = Role::all();


        return view('users.create', compact('roles'));
    }


    public function store(Request $request)
    {
        $request->validate([

            'name' => 'required',

            'email' => 'required|email|unique:users',

            'password' => 'required|min:8',

            'role' => 'required|exists:roles,name',

            'is_active' => 'required|boolean',


        ]);


        $user = User::create([

            'name' => $request->name,

            'email' => $request->email,

            'password' => Hash::make($request->password),

        ]);


        // Assign Spatie role

        $user->assignRole($request->role);



        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully');
    }


    public function show(User $user)
    {
        //
    }


    public function edit(User $user)
    {
        $roles = Role::with('permissions')->get();

        $permissions = Permission::all();

        return view('users.edit', compact(
            'user',
            'roles',
            'permissions'
        ));
    }


    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
            'is_active' => 'required|boolean',
        ]);

        $user->update([
            'is_active' => $request->is_active,
        ]);


        // Update user role
        $user->syncRoles([$request->role]);


        // Update role permissions
        $role = Role::findByName($request->role);

        if ($request->has('permissions')) {

            $role->syncPermissions($request->permissions);
        } else {

            $role->syncPermissions([]);
        }


        // Update user permissions
        $user->syncPermissions($request->permissions ?? []);


        return redirect()
            ->route('users.index')
            ->with('success', 'User permissions updated successfully');
    }


    public function destroy(User $user)
    {
        //
    }
}
