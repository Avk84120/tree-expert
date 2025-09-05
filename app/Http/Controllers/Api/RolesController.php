<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    // List all roles
    public function index()
    {
        return response()->json(Role::all());
    }

    // Create new role (admin only)
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'description' => 'nullable|string'
        ]);
        $role = Role::create($data);
        return response()->json($role, 201);
    }

    // Assign role to a user
    public function assignRole(Request $request, $userId)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = User::findOrFail($userId);
        $user->role_id = $request->role_id;
        $user->save();

        return response()->json(['message' => 'Role updated successfully', 'user' => $user]);
    }

    // Show user role
    public function userRole($userId)
    {
        $user = User::with('role')->findOrFail($userId);
        return response()->json([
            'user' => $user->name,
            'role' => $user->role->name
        ]);
    }

    // Delete a role (admin only)
    public function destroy($id)
    {
        Role::findOrFail($id)->delete();
        return response()->json(['message' => 'Role deleted']);
    }
}
