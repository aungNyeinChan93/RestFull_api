<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    // index
    public function index()
    {
        $users = User::all();
        return view('Tests.index', compact('users'));
    }

    // show
    public function show(User $user)
    {
        return view('Tests.show',compact('user'));
    }
}
