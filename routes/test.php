<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TestController;

Route::get('tests',[TestController::class,'index'])->name('tests.index');
Route::get('tests/{user}',[TestController::class,'show'])->name('tests.show');


// auth
Route::get('register',[RegisterController::class,'register'])->name('register');
Route::post('register',[RegisterController::class,'create'])->name('register.create');
Route::get('login',[LoginController::class,'login'])->name('login');
Route::post('login',[LoginController::class,'store'])->name('login.store');
