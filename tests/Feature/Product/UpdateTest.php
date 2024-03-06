<?php

use App\Models\Product;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

uses()->group('product');

it('can not update product if no authenticate', function () {
    $response = $this->patch('/api/products/1', [], ['Accept' => 'application/json']);

    $response->assertStatus(401);
});

it('can not update product not related to current user', function () {
    Sanctum::actingAs(User::factory()->createQuietly());

    $product = Product::factory()->createQuietly();

    $response = $this->patch('/api/products/' . $product->id, [], ['Accept' => 'application/json']);

    $response->assertStatus(404);
});

it('can update product related to current user', function () {
    $user = User::factory()->createQuietly();

    Sanctum::actingAs($user);

    $product = Product::factory(['user_id' => $user->id])->createQuietly();

    $response = $this->patch('/api/products/' . $product->id,
        [
            'name' => fake()->name,
        ],
        ['Accept' => 'application/json']);

    $response->assertOk();
});

it('can update product', function () {
    $user = User::factory()->createQuietly();

    Sanctum::actingAs($user);

    $product = Product::factory(['user_id' => $user->id])->createQuietly();

    $response = $this->patch('/api/products/' . $product->id,
        [
            'name' => fake()->name,
            'code' => fake()->randomElement([
                (string) fake()->randomNumber(),
                fake()->numerify(Product::CUSTOM_CODE_PREFIX . '###'),
            ]),
            'description' => fake()->sentence,
            'image' => fake()->url,
            'nutriscore' => fake()->randomElement(['a', 'b', 'c', 'd', 'e']),
            'novagroup' => fake()->numberBetween(1, 3),
            'ecoscore' => fake()->randomElement(['a', 'b', 'c', 'd', 'e']),
            'finished_at' => fake()->dateTimeBetween('-1 month', '+1 year')->format('d/m/Y'),
            'added_to_purchase_list_at' => fake()->dateTimeBetween('-1 month', '+1 year')->format('d/m/Y'),
            'expiration_dates' => [
                ['date' => fake()->date('d/m/Y')],
                ['date' => fake()->date('d/m/Y')],
            ],
        ],
        ['Accept' => 'application/json']);

    $response->assertOk();
});

it('can not update unexpected data', function () {
    $user = User::factory()->createQuietly();

    Sanctum::actingAs($user);

    $product = Product::factory(['user_id' => $user->id, 'code' => 'code correct'])->createQuietly();

    $this->patch('/api/products/' . $product->id, ['code' => 'wrong code'], ['Accept' => 'application/json']);

    $this->assertDatabaseHas('products', ['code' => 'code correct']);
    $this->assertDatabaseMissing('products', ['code' => 'wrong code']);
});

it('can not update product with invalid data', function (array $invalidBody) {
    $user = User::factory()->createQuietly();

    Sanctum::actingAs($user);

    $product = Product::factory(['user_id' => $user->id, 'code' => 'code correct'])->createQuietly();

    $response = $this->patch('/api/products/' . $product->id, $invalidBody, ['Accept' => 'application/json']);

    $response->assertUnprocessable();
})->with([[
    ['name' => null, 'expiration_dates' => [['date' => fake()->date('d/m/Y')]]],
    ['name' => 123, 'expiration_dates' => [['date' => fake()->date('d/m/Y')]]],
    ['name' => 'banana', 'expiration_dates' => [['date' => fake()->date('d/m/Y')]], 'description' => 123],
    ['name' => 'banana', 'expiration_dates' => [['date' => fake()->date('d/m/Y')]], 'image' => 123],
    ['name' => 'banana', 'expiration_dates' => [['date' => fake()->date('d/m/Y')]], 'nutriscore' => 123],
    ['name' => 'banana', 'expiration_dates' => [['date' => fake()->date('d/m/Y')]], 'nutriscore' => 'ab'],
    ['name' => 'banana', 'expiration_dates' => [['date' => fake()->date('d/m/Y')]], 'novagroup' => ''],
    ['name' => 'banana', 'expiration_dates' => [['date' => fake()->date('d/m/Y')]], 'novagroup' => 5],
    ['name' => 'banana', 'expiration_dates' => [['date' => fake()->date('d/m/Y')]], 'ecoscore' => 123],
    ['name' => 'banana', 'expiration_dates' => [['date' => fake()->date('d/m/Y')]], 'ecoscore' => 'ab'],
    ['name' => 'banana', 'expiration_dates' => [['date' => fake()->date('d/m/Y')]], 'finished_at' => 1],
    ['name' => 'banana', 'expiration_dates' => [['date' => fake()->date('d/m/Y')]], 'finished_at' => '2024/01/01'],
    ['name' => 'banana', 'to_purchase' => [['date' => fake()->date('d/m/Y')]], 'finished_at' => 1],
    ['name' => 'banana', 'to_purchase' => [['date' => fake()->date('d/m/Y')]], 'finished_at' => '2024/01/01'],
]]);
