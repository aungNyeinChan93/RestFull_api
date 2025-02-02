<?php

namespace App\Http\Controllers;

use Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //login
    public function login()
    {
        return view('auth.login');
    }

    // store
    public function store(Request $request)
    {

        $fields = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,'. request('user')],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!auth::attempt($fields)) {
            return back()->withErrors([
                'email' => 'password wrong!',
            ])->onlyInput('email');
        }

        Auth::login($user);

        return $user;
    }
}
