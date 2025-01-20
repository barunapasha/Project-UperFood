<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function scopeBetweenUsers($query, $user1, $user2)
    {
        return $query->where(function ($q) use ($user1, $user2) {
            $q->where('sender_id', $user1)
              ->where('receiver_id', $user2);
        })->orWhere(function ($q) use ($user1, $user2) {
            $q->where('sender_id', $user2)
              ->where('receiver_id', $user1);
        });
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update(['is_read' => true]);
        }
    }
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('H:i');
    }

    public function isSender($userId)
    {
        return $this->sender_id === $userId;
    }

    public function isReceiver($userId)
    {
        return $this->receiver_id === $userId;
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getDateAttribute()
    {
        return $this->created_at->format('Y-m-d');
    }

    public function scopePrunable($query)
    {
        return $query->where('created_at', '<=', now()->subMonths(6));
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($chat) {
        });

        static::updated(function ($chat) {
            if ($chat->isDirty('is_read')) {
            }
        });
    }
}