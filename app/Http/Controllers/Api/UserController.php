<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PhpParser\Node\Stmt\TryCatch;

class UserController extends Controller
{
    //index
    public function index()
    {
        try {
            return response()->json([
                'users' => User::query()->get(['name','email']),
                'message'=> 'success',
            ], 200);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
