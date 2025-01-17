<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'warung_id',
        'total_amount',
        'status'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
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