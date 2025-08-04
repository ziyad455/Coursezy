<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Rating;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 5 coaches
        $coaches = User::factory(5)->create(['role' => 'coach']);

        // Create 20 students
        $students = User::factory(20)->create(['role' => 'student']);

        // Create 5 categories
        $categories = Category::factory(5)->create();

        // Create 10 courses, each linked to a random coach and category
        $courses = Course::factory(10)->make()->each(function ($course) use ($coaches, $categories) {
            $course->coach_id = $coaches->random()->id;
            $course->category_id = $categories->random()->id;
            $course->save();
        });

        // Enroll students randomly to courses (50 enrollments)
        Enrollment::factory(50)->make()->each(function ($enrollment) use ($students, $courses) {
            $enrollment->student_id = $students->random()->id;
            $enrollment->course_id = $courses->random()->id;
            $enrollment->save();
        });

        // Create 50 ratings by random students on random courses
        Rating::factory(50)->make()->each(function ($rating) use ($students, $courses) {
            $rating->student_id = $students->random()->id;
            $rating->course_id = $courses->random()->id;
            $rating->save();
        });
    }
}
