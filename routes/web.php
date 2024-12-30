<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

Route::get('/', [PageController::class, 'landing'])->name('landing');
Route::get('/information', [PageController::class, 'information'])->name('information');