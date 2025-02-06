<?php

use App\Models\User;
use App\Mail\TestMail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\TestController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

Route::get('tests/request', [TestController::class, 'request'])->name('test.request');
Route::get('tests', [TestController::class, 'index'])->name('tests.index');
Route::get('tests/{user}', [TestController::class, 'show'])->name('tests.show');


// auth
Route::get('register', [RegisterController::class, 'register'])->name('register');
Route::post('register', [RegisterController::class, 'create'])->name('register.create');
Route::get('login', [LoginController::class, 'login'])->name('login');
Route::post('login', [LoginController::class, 'store'])->name('login.store');


// test

// user
Route::get('test/users', function () {
    $user = User::with('role')->find(1);
    dd($user->role->name);
});

// mail
Route::get('test/mail', function () {
    $attach = 'sample attach data';
    Mail::to(User::find(10))->send(new TestMail(User::find(10), $attach));
    return 'mail';
});

// array
Route::get('array', function () {

    $users = User::get();
    // $name = [];
    // foreach ($users as $key => $user) {
    //     $name[] = $user->name;
    // }
    // return [...$name];

    // $name = $users->pluck('name');

    $name = array_map(fn($user) => $user['name'], $users->toArray());

    dd($name);

    // multipleImages
    $path = array_map(fn($file) => $file->store('/image/', 'public'), request('images'));

    // Image::create([
    //     'url' => [...$path],
    //     "products_id" => request()->user()->id,
    // ]);


    dd($path);


});

// tests/eloquent

Route::get('tests/eloquent/orders', function () {
    $p = User::with(['role', 'orders'])->whereHas('orders.orders_products.product', function (Builder $builder) {
        return $builder->where('id', 9);    
    })->get();

    return $p;
});

