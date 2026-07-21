<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function index()
    {
        $users = User::with('roles')->latest()->get();

        return view('users.index', compact('users'));
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
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