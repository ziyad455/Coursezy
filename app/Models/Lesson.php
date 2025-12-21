<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'title',
        'description',
        'type',
        'video_url',
        'content',
        'file_url',
        'duration',
        'order',
        'is_preview',
        'is_published',
        'metadata'
    ];

    protected $casts = [
        'is_preview' => 'boolean',
        'is_published' => 'boolean',
        'order' => 'integer',
        'duration' => 'integer',
        'metadata' => 'array'
    ];

    /**
     * Get the section that owns the lesson.
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the course through the section.
     */
    public function course()
    {
        return $this->hasOneThrough(
            Course::class,
            Section::class,
            'id', // Foreign key on sections table
            'id', // Foreign key on courses table
            'section_id', // Local key on lessons table
            'course_id' // Local key on sections table
        );
    }

    /**
     * Scope a query to only include published lessons.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope a query to only include preview lessons.
     */
    public function scopePreview($query)
    {
        return $query->where('is_preview', true);
    }

    /**
     * Get the next order value for a new lesson in a section.
     */
    public static function getNextOrder($sectionId)
    {
        return static::where('section_id', $sectionId)->max('order') + 1 ?? 1;
    }

    /**
     * Get formatted duration (HH:MM:SS).
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration) {
            return null;
        }

        $hours = floor($this->duration / 3600);
        $minutes = floor(($this->duration % 3600) / 60);
        $seconds = $this->duration % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * Check if lesson is accessible to a user.
     */
    public function isAccessibleTo($user)
    {
        // Preview lessons are accessible to everyone
        if ($this->is_preview) {
            return true;
        }

        // Check if user is enrolled in the course
        if ($user) {
            return $this->course->enrollments()
                ->where('student_id', $user->id)
                ->exists();
        }

        return false;
    }
}
