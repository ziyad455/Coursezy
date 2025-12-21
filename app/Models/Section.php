<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'order',
        'is_published'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'order' => 'integer'
    ];

    /**
     * Get the course that owns the section.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the lessons for the section.
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    /**
     * Get published lessons for the section.
     */
    public function publishedLessons()
    {
        return $this->hasMany(Lesson::class)
            ->where('is_published', true)
            ->orderBy('order');
    }

    /**
     * Scope a query to only include published sections.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Get the next order value for a new section in a course.
     */
    public static function getNextOrder($courseId)
    {
        return static::where('course_id', $courseId)->max('order') + 1 ?? 1;
    }
}
