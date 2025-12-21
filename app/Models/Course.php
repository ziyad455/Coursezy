<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property float $price
 * @property string|null $thumbnail
 * @property int $coach_id
 * @property int $category_id
 * @property string $status
 */
class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'price',
        'thumbnail',
        'coach_id',
        'category_id',
        'status'
    ];

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class)->orderBy('order');
    }

    public function publishedSections()
    {
        return $this->hasMany(Section::class)
            ->where('is_published', true)
            ->orderBy('order');
    }

    /**
     * Get total duration of all lessons in the course (in seconds)
     */
    public function getTotalDurationAttribute()
    {
        return $this->sections()->with('lessons')->get()
            ->flatMap->lessons
            ->where('is_published', true)
            ->sum('duration');
    }

    /**
     * Get formatted total duration (e.g., "32h 15m")
     */
    public function getFormattedDurationAttribute()
    {
        $totalSeconds = $this->total_duration;
        if (!$totalSeconds) {
            return '0h';
        }

        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);

        if ($hours > 0 && $minutes > 0) {
            return $hours . 'h ' . $minutes . 'm';
        } elseif ($hours > 0) {
            return $hours . 'h';
        } else {
            return $minutes . 'm';
        }
    }

    /**
     * Get total number of lessons in the course
     */
    public function getTotalLessonsAttribute()
    {
        return $this->sections()->withCount('lessons')->get()->sum('lessons_count');
    }

    /**
     * Get total number of published lessons
     */
    public function getTotalPublishedLessonsAttribute()
    {
        return Lesson::whereHas('section', function ($query) {
            $query->where('course_id', $this->id);
        })->where('is_published', true)->count();
    }

    /**
     * Get average rating
     */
    public function getAverageRatingAttribute()
    {
        return round($this->ratings()->avg('rating') ?? 0, 1);
    }

    /**
     * Get total number of students enrolled
     */
    public function getTotalStudentsAttribute()
    {
        return $this->enrollments()->count();
    }

    /**
     * Get course level based on content or default
     */
    public function getLevelAttribute($value)
    {
        // If level is stored in database, return it
        if (isset($this->attributes['level'])) {
            return $this->attributes['level'];
        }

        // Otherwise, determine based on number of lessons
        $totalLessons = $this->total_published_lessons;
        if ($totalLessons > 50) {
            return 'Advanced';
        } elseif ($totalLessons > 20) {
            return 'Intermediate';
        } else {
            return 'Beginner';
        }
    }

    /**
     * Check if user is enrolled in this course
     */
    public function isEnrolledBy($userId)
    {
        if (!$userId) {
            return false;
        }
        return $this->enrollments()->where('student_id', $userId)->exists();
    }

    /**
     * Get the number of coding exercises (lessons with type 'exercise')
     */
    public function getTotalExercisesAttribute()
    {
        return Lesson::whereHas('section', function ($query) {
            $query->where('course_id', $this->id);
        })->where('type', 'exercise')
            ->where('is_published', true)
            ->count();
    }

    /**
     * Get the number of projects (lessons with type 'project')
     */
    public function getTotalProjectsAttribute()
    {
        return Lesson::whereHas('section', function ($query) {
            $query->where('course_id', $this->id);
        })->where('type', 'project')
            ->where('is_published', true)
            ->count();
    }
}
