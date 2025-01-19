<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'warung_id',
        'total_amount',
        'status',
        'payment_token',
        'payment_type',
        'payment_details',
        'order_number'
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

    public function setStatusSuccess()
    {
        $this->status = 'success';
        $this->save();
    }

    public function setStatusPending()
    {
        $this->status = 'pending';
        $this->save();
    }

    public function setStatusFailed()
    {
        $this->status = 'failed';
        $this->save();
    }

    public function setStatusExpired()
    {
        $this->status = 'expired';
        $this->save();
    }
}
