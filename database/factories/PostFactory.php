<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   public function definition(): array
{
    return [
        'title' => $this->faker->sentence(), // Thêm dòng này
        'slug' => $this->faker->slug(),
        'content' => $this->faker->paragraph(),
        'user_id' => \App\Models\User::factory(),
        'category_id' => \App\Models\Category::factory(),
        'status' => 'published',
    ];
}
}
