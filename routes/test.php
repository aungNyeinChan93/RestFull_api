<?php

use App\Models\User;
use App\Mail\TestMail;
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
