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
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ChatController;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginPage'])->name('login');
Route::post('/login', [LoginController::class, 'processLogin'])->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Public Routes
Route::get('/', [PageController::class, 'landing'])->name('landing');
Route::get('/information', [PageController::class, 'information'])->name('information');

// Registration Routes
Route::get('/sign-up', [SignUpController::class, 'show'])->name('sign-up');
Route::post('/sign-up', [SignUpController::class, 'store'])->name('sign-up.store');



Route::middleware(['auth'])->group(function () {
    // Home & Profile
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/search', [HomeController::class, 'search'])->name('search');

    Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

    // Warung Routes
    Route::get('/warung/kantin-atas', [WarungController::class, 'kantinAtas'])->name('kantin-atas');
    Route::get('/warung/kantin-bawah', [WarungController::class, 'kantinBawah'])->name('kantin-bawah');
    Route::get('/warung/{slug}', [WarungDetailController::class, 'show'])->name('warung.detail');

    // Category Routes
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/category/new', 'new')->name('category.new');
        Route::get('/category/favorite', 'favorite')->name('category.favorite');
        Route::get('/category/local', 'local')->name('category.local');
        Route::get('/category/bestseller', 'bestseller')->name('category.bestseller');
    });

    // Cart Routes
    Route::post('/cart/items', [CartController::class, 'addItem'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/cart/items', [CartController::class, 'getItems'])->name('cart.items');
    Route::get('/cart/count', [CartController::class, 'getCartCount']);
    Route::patch('/cart/items/{id}', [CartController::class, 'updateItem'])->name('cart.update');
    Route::delete('/cart/items/{id}', [CartController::class, 'removeItem'])->name('cart.remove');

    // Checkout Routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])
        ->name('checkout.process')
        ->middleware('auth');

    // Payment Routes
    Route::post('/payments/notification', [PaymentController::class, 'notification'])->name('payment.notification');
    Route::get('/payments/complete', [PaymentController::class, 'complete'])->name('payment.complete');
    Route::get('/payments/failed', [PaymentController::class, 'failed'])->name('payment.failed');
    Route::get('/payments/unfinish', [PaymentController::class, 'unfinish'])->name('payment.unfinish');

    // Chat Routes
    Route::get('/chat/users', [ChatController::class, 'getUsers']);
    Route::get('/chat/messages/{user}', [ChatController::class, 'getMessages']);
    Route::post('/chat/send/{user}', [ChatController::class, 'sendMessage']);
    Route::post('/chat/mark-read/{user}', [ChatController::class, 'markAsRead']);
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Warung Management
    Route::post('/warung', [AdminController::class, 'storeWarung'])->name('warung.store');
    Route::put('/warung/{id}', [AdminController::class, 'updateWarung'])->name('warung.update');
    Route::delete('/warung/{id}', [AdminController::class, 'deleteWarung'])->name('warung.delete');
    Route::get('/warung/{id}/menu', [AdminController::class, 'warungMenu'])->name('warung.menu');

    // Menu Item Management
    Route::post('/warung/{warungId}/category/{categoryId}/menu', [AdminController::class, 'createMenuItem'])->name('menu.store');
    Route::put('/menu/{id}', [AdminController::class, 'updateMenuItem'])->name('menu.update');
    Route::delete('/menu/{id}', [AdminController::class, 'deleteMenuItem'])->name('menu.delete');

    // Menu Category Management
    Route::post('/warung/{warungId}/category', [AdminController::class, 'createMenuCategory'])->name('category.store');
    Route::put('/category/{id}', [AdminController::class, 'updateMenuCategory'])->name('category.update');
    Route::delete('/category/{id}', [AdminController::class, 'deleteMenuCategory'])->name('category.delete');
});
