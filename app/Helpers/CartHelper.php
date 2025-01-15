<?php

namespace App\Helpers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CartHelper
{
    public static function getCartItemsCount()
    {
        if (!Auth::check()) {
            return 0;
        }

        $userId = Auth::id();
        return Cache::remember("cart_count_" . $userId, now()->addMinutes(5), function () use ($userId) {
            // Get all carts for the user and sum the quantities of their items
            return Cart::where('user_id', $userId)
                      ->with('items')
                      ->get()
                      ->pluck('items')
                      ->flatten()
                      ->sum('quantity');
        });
    }
}