<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    //index
    public function index(Request $request)
    {
        $users = User::query()->get();

        return response()->json([
            'message' => 'success',
            'users' => $users,
        ], 200);
    }

    // show
    public function show(User $user)
    {
        return response()->json([
            'message' => 'success',
            'user' => $user
        ], 200);
    }

    // destroy
    public function destroy(User $user, Request $request)
    {
        if ($user->role !== 'admin' && $request->user()->role === 'admin') {

            $user->delete();

            return response()->json([
                'message' => 'success',
                'userName' => $user->name,
            ], 200);

        }

        return response()->json([
            'message' => 'fail',
            'user_role' => $user->role
        ], 400);
    }
}
