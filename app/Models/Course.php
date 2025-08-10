<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
