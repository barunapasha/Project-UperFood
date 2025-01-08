<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SignUpController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WarungController;
use App\Http\Controllers\WarungDetailController;
use App\Http\Controllers\AdminController;

Route::get('/login', [LoginController::class, 'showLoginPage'])->name('login');
Route::post('/login', [LoginController::class, 'processLogin'])->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', [PageController::class, 'landing'])->name('landing');
Route::get('/information', [PageController::class, 'information'])->name('information');

Route::get('/sign-up', [SignUpController::class, 'show'])->name('sign-up');
Route::post('/sign-up', [SignUpController::class, 'store'])->name('sign-up.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    
    Route::get('/warung/kantin-atas', [WarungController::class, 'kantinAtas'])->name('kantin-atas');
    Route::get('/warung/kantin-bawah', [WarungController::class, 'kantinBawah'])->name('kantin-bawah');
    Route::get('/warung/{id}', [WarungDetailController::class, 'show'])->name('warung.detail');

});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/warung/{id}/menu', [AdminController::class, 'warungMenu'])->name('admin.warung.menu');
    
    Route::post('/admin/warung/{warungId}/category/{categoryId}/menu', [AdminController::class, 'createMenuItem'])->name('admin.menu.create');
    Route::put('/admin/menu/{id}', [AdminController::class, 'updateMenuItem'])->name('admin.menu.update');
    Route::delete('/admin/menu/{id}', [AdminController::class, 'deleteMenuItem'])->name('admin.menu.delete');
    
    Route::post('/admin/warung/{warungId}/category', [AdminController::class, 'createMenuCategory'])->name('admin.category.create');
    Route::put('/admin/category/{id}', [AdminController::class, 'updateMenuCategory'])->name('admin.category.update');
    Route::delete('/admin/category/{id}', [AdminController::class, 'deleteMenuCategory'])->name('admin.category.delete');
    
    Route::post('/admin/warung', [AdminController::class, 'createWarung'])->name('admin.warung.create');
    Route::put('/admin/warung/{id}', [AdminController::class, 'updateWarung'])->name('admin.warung.update');
    Route::delete('/admin/warung/{id}', [AdminController::class, 'deleteWarung'])->name('admin.warung.delete');
});