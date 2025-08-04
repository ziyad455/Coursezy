<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 10, 200),
            'thumbnail' => fake()->imageUrl(640, 480, 'education'),
            'coach_id' => User::factory()->create(['role' => 'coach'])->id,
            'category_id' => Category::factory(),
            'created_at' => now(),
        ];
    }
}
