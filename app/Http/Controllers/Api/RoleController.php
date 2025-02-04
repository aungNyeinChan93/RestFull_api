<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    //
    //index
    public function index()
    {
        $roles = Role::query()->get();
        return response()->json([
            'message' => 'success',
            'roles' => $roles,
        ], 200);
    }

    // store
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string|unique:roles,name'
        ]);

        $role = Role::create($fields);

        return response()->json([
            'message' => 'success',
            'role' => $role,
        ], 200);
    }

    // update
    public function update(Role $role, Request $request)
    {
        $request->validate(['name' => 'required|string|unique:roles,name']);

        $role->update(['name' => $request->name]);

        return response()->json([
            'message' => 'success',
            'role' => $role
        ], 200);
    }

    // destroy
    public function destroy(Role $role)
    {
        if (request()->user()->role->name === 'admin') {
            $role->delete();
            return response()->json([
                'message' => 'success',
                'role' => $role
            ], 200);
        }

        return response()->json([
            'message' => 'fail',
            'user_role' => request()->user()->role
        ], 403);
    }
}
