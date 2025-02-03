<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //register
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create($fields);

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('register-token')->plainTextToken,
        ], 201);
    }

    // login
    public function login(Request $request)
    {
        $fileds = $request->validate([
            'email' => 'required|email|',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $fileds['email'])->first();

        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // For single login
        if($user->tokens()){
            $user->tokens()->delete();
        }

        return response()->json([
            'message' => 'success',
            'user' => $user,
            'token' => $user->createToken('login-token')->plainTextToken,
        ], 200);
    }

    // logout
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'success',
            'user' => $request->user()
        ],200);
    }
}
