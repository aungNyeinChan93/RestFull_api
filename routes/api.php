<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// token generate
Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);

    return ['token' => $token->plainTextToken];
});

// auth
Route::post('register',[AuthController::class,'register']);

// 
Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('users',[UserController::class,'index'])->name('users.index');
});
