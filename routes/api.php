<?php

use App\Http\Controllers\Api\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserControler;

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

// Users
Route::middleware(['auth:sanctum', 'json_response'])->group(function () {

    // users profile
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/profile', [UserController::class, 'profile']);
    Route::get('users/{user}', [UserController::class, 'show']);
    Route::delete('users/destroy', [UserController::class, 'destroy']);
    Route::post('users/profile-update', [UserController::class, 'profile_update']);
    Route::put('users/password-change', [UserController::class, 'changePassword']);

    // Myorder
    Route::get('orders', [OrderController::class, 'myOrder']);


    // event
    Route::get('events', [EventController::class, 'generate_token']);

    // categories
    Route::prefix('admin')->group(function () {
        Route::apiResource('categories', CategoryController::class);
    });

});

// Admins
Route::middleware(['auth:sanctum', 'admin', 'json_response'])->group(function () {

    Route::prefix('admin')->group(function () {

        // users
        Route::get('users', [AdminUserControler::class, 'index']);
        Route::get('users/{user}', [AdminUserControler::class, 'show']);
        Route::delete('users/{user}/destroy', [AdminUserControler::class, 'destroy']);

        // roles
        Route::apiResource('roles', RoleController::class);

        // products
        Route::apiResource('products', ProductController::class);

        // order
        Route::apiResource('orders', OrderController::class);

    });

});




// for testing api
Route::post('multiImage', function () {

    // $files = request()->file('images', []);

    $files = request(['images']);

    dd(gettype($files));

    if (!is_array($files)) {
        $files = [$files];
    }

    // $paths = [];
    // foreach ($files as $key => $file) {
    //     $paths[] = $file->store('image', 'public');
    // }

    // $number = array_map(fn($n)=> $n ,[1,23,3,2,13,]);
    // dd($number);

    $paths = array_map(fn($file) => $file->store('/image/', 'public'), $files);

    return $paths;

    foreach ($paths as $key => $path) {
        Image::create([
            'url' => $path,
            "products_id" => request()->user()->id,
        ]);
    }

});
