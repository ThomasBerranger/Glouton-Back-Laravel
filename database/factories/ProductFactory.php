<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
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
    public function definition(): array
    {
        $finishedAt = fake()->optional()->dateTimeBetween('-1 month', '+1 year');
        $addedToPurchaseListAt = fake()->optional()->dateTimeBetween('-1 month', '+1 year');

        return [
            'name' => fake()->word,
            'code' => fake()->randomElement([
                fake()->randomNumber(),
                fake()->numerify(Product::CUSTOM_CODE_PREFIX . '###')
            ]),
            'user_id' => User::factory()->createQuietly(),
            'description' => fake()->optional()->sentence,
            'image' => fake()->optional()->url,
            'nutriscore' => fake()->optional()->randomElement(['a', 'b', 'c', 'd', 'e', null]),
            'novagroup' => fake()->optional()->numberBetween(1, 3),
            'ecoscore' => fake()->optional()->randomElement(['a', 'b', 'c', 'd', 'e', null]),
            'finished_at' => $finishedAt ? $finishedAt->format('d/m/y') : $finishedAt,
            'added_to_purchase_list_at' => $addedToPurchaseListAt ? $addedToPurchaseListAt->format('d/m/y') : $addedToPurchaseListAt,
        ];
    }
}
