<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserControler;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

// check user by auth:sanctum
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// token generate
Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);
    return ['token' => $token->plainTextToken];
})->middleware(['auth:sanctum']);

// auth
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);

//Users
Route::middleware(['auth:sanctum'])->group(function () {

    // users
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{user}', [UserController::class, 'show']);
    Route::delete('users/destroy', [UserController::class, 'destroy']);
    Route::post('users/profile-update', [UserController::class, 'profile_update']);
    Route::put('users/password-change', [UserController::class, 'changePassword']);

    // event
    Route::get('events', [EventController::class, 'generate_token']);

    // categories
    Route::resource('categories', CategoryController::class);

    // roles
    Route::resource('roles', RoleController::class);
});


//Admins
Route::middleware(['auth:sanctum', 'admin'])->group(function () {

    Route::prefix('admin')->group(function () {
        Route::get('users', [AdminUserControler::class, 'index']);
        Route::get('users/{user}', [AdminUserControler::class, 'show']);
        Route::delete('users/{user}/destroy', [AdminUserControler::class, 'destroy']);
    });
});
