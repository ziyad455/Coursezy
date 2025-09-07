<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    protected $fillable = [
        'type',
        'description',
        'data',
        'user_id',
        'coach_id',
        'course_id',
        'student_id',
        'amount',
        'rating',
        'is_read'
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Activity types
    const TYPE_ENROLLMENT = 'enrollment';
    const TYPE_REVIEW = 'review';
    const TYPE_PAYMENT = 'payment';
    const TYPE_COURSE_VIEW = 'course_view';
    const TYPE_COURSE_CLICK = 'course_click';
    const TYPE_MESSAGE = 'message';

    /**
     * Get the user who performed the activity
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the coach related to this activity
     */
    public function coach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    /**
     * Get the student (for enrollments)
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the course related to this activity
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Scope for unread activities
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for coach activities
     */
    public function scopeForCoach($query, $coachId)
    {
        return $query->where('coach_id', $coachId);
    }

    /**
     * Get formatted time ago
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get icon based on activity type
     */
    public function getIconAttribute()
    {
        return match($this->type) {
            self::TYPE_ENROLLMENT => 'fas fa-user-plus',
            self::TYPE_REVIEW => 'fas fa-star',
            self::TYPE_PAYMENT => 'fas fa-dollar-sign',
            self::TYPE_COURSE_VIEW => 'fas fa-eye',
            self::TYPE_COURSE_CLICK => 'fas fa-mouse-pointer',
            self::TYPE_MESSAGE => 'fas fa-envelope',
            default => 'fas fa-bell'
        };
    }

    /**
     * Get color based on activity type
     */
    public function getColorAttribute()
    {
        return match($this->type) {
            self::TYPE_ENROLLMENT => 'green',
            self::TYPE_REVIEW => 'yellow',
            self::TYPE_PAYMENT => 'blue',
            self::TYPE_COURSE_VIEW => 'purple',
            self::TYPE_COURSE_CLICK => 'indigo',
            self::TYPE_MESSAGE => 'gray',
            default => 'gray'
        };
    }
}
