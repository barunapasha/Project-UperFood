<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'warung_id',
        'total_amount'
    ];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function warung()
    {
        return $this->belongsTo(Warung::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCacheKey()
    {
        return "cart_user_" . $this->user_id;
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($cart) {
            Cache::put($cart->getCacheKey(), $cart->fresh(), now()->addDays(30));
        });

        static::deleted(function ($cart) {
            Cache::forget($cart->getCacheKey());
        });
    }
}