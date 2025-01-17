<?php

namespace App\Helpers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartHelper
{
    public static function getCartItemsCount()
    {
        $cartId = Session::get('cart_id');
        $count = 0;

        if ($cartId) {
            $count = CartItem::where('cart_id', $cartId)->sum('quantity');
        } elseif (Auth::check()) {
            $count = Cart::where('user_id', Auth::id())
                ->join('cart_items', 'carts.id', '=', 'cart_items.cart_id')
                ->sum('cart_items.quantity');
        }

        return $count;
    }
}