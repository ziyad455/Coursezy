<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Enrollment>
 */
class EnrollmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_id' => User::factory()->create(['role' => 'student'])->id,
            'course_id' => Course::factory(),
            'purchased_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'created_at' => now(),
        ];
    }
}
