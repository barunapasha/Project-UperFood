<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}