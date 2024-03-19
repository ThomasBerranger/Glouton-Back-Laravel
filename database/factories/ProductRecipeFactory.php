<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductRecipe>
 */
class ProductRecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory()->createQuietly(),
            'recipe_id' => Recipe::factory()->createQuietly(),
            'quantity' => fake()->randomFloat(2),
            'quantity_unity' => fake()->optional()->word,
        ];
    }
}
