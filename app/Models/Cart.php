<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'warung_id',
        'total_amount',
        'session_id'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2'
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($cart) {
            if (!$cart->session_id) {
                $cart->session_id = session()->getId();
            }
        });
    }
}