<?php

namespace Database\Factories;

use App\Helpers\RandomImage;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->text(1000),
            'image' => fake()->imageUrl(RandomImage::IMAGE_WIDTH, RandomImage::IMAGE_HEIGHT, RandomImage::IMAGE_CATEGORIES),
            'category_id' => Category::inRandomOrder()->first()->id,
        ];
    }
}
