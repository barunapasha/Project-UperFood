<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SignUpController;
use App\Http\Controllers\LoginController;

Route::get('/login', [LoginController::class, 'showLoginPage'])->name('login');
Route::post('/login', [LoginController::class, 'processLogin'])->name('login.process');

Route::get('/', [PageController::class, 'landing'])->name('landing');

Route::get('/information', [PageController::class, 'information'])->name('information');

Route::get('/sign-up', [SignUpController::class, 'show'])->name('sign-up');
Route::post('/sign-up', [SignUpController::class, 'store'])->name('sign-up.store');
