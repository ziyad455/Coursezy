<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Message extends Model
{
    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'message',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    /**
     * Get the last message between two users
     */
    public static function getLastMessage($userId1, $userId2)
    {
        return self::where(function($query) use ($userId1, $userId2) {
            $query->where('from_user_id', $userId1)
                  ->where('to_user_id', $userId2);
        })->orWhere(function($query) use ($userId1, $userId2) {
            $query->where('from_user_id', $userId2)
                  ->where('to_user_id', $userId1);
        })
        ->orderBy('created_at', 'desc')
        ->first();
    }

    /**
     * Get unread count for a specific conversation
     */
    public static function getUnreadCount($fromUserId, $toUserId)
    {
        return self::where('from_user_id', $fromUserId)
                   ->where('to_user_id', $toUserId)
                   ->where('is_read', false)
                   ->count();
    }
}
