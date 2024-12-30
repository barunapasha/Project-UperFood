<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SignUpController;

// Route untuk Landing Page
Route::get('/', [PageController::class, 'landing'])->name('landing');

// Route untuk Halaman Informasi
Route::get('/information', [PageController::class, 'information'])->name('information');

// Route untuk Halaman Sign-Up
Route::get('/sign-up', [SignUpController::class, 'show'])->name('sign-up');
Route::post('/sign-up', [SignUpController::class, 'store'])->name('sign-up.store');
