<?php

namespace Database\Factories;

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
        return [
            'name' => 'SKYR',
            'code' => '3033491454080',
            'user_id' => User::factory()->createQuietly(),
            'expiration_dates' => $this->randomExpirationDates(),
            'description' => 'Spécialité laitière nature',
            'image' => 'https://images.openfoodfacts.org/images/products/303/349/145/4080/front_fr.134.400.jpg',
            'nutriscore' => 'a',
            'novagroup' => 3,
            'ecoscore' => 'b',
            'finished_at' => fake()->dateTimeBetween('now', '+ 1 month')->format('d/m/Y H:m'),
            'added_to_purchase_list_at' => null,
        ];
    }

    private function randomExpirationDates(): string
    {
        $expirationDates = [];

        for ($i = 0; $i < fake()->numberBetween(1, 3); $i++) {
            $expirationDates[] = fake()->dateTimeBetween('now', '+ 1 month')->format('d/m/Y');
        }

        return json_encode($expirationDates);
    }
}
