<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rating>
 */
class RatingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_id' => User::factory()->create(['role' => 'student'])->id,
            'course_id' => Course::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->sentence(),
            'created_at' => now(),
        ];
    }
}
