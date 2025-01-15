<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'menu_item_id',
        'quantity',
        'price',
        'subtotal'
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($item) {
            if ($item->cart) {
                Cache::put(
                    $item->cart->getCacheKey(),
                    $item->cart->fresh(),
                    now()->addDays(30)
                );
            }
        });
    }
}