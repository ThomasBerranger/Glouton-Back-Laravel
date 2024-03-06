<?php

use App\Models\Product;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

uses()->group('product');

it('can not post product if no authenticate', function () {
    $response = $this->post('/api/products', [], ['Accept' => 'application/json']);

    $response->assertUnauthorized();
    $response->assertSee(['message' => 'Unauthenticated.']);
});

it('can post product related to current user', function () {
    $user = User::factory()->createQuietly();

    Sanctum::actingAs($user);

    $response = $this->post('/api/products',
        [
            'name' => fake()->name,
            'expiration_dates' => [
                ['date' => fake()->date('d/m/Y')],
            ],
        ],
        ['Accept' => 'application/json']);

    $response->assertCreated();

    $this->assertEquals(1, $user->products()->count());
});

it('can post product', function () {
    Sanctum::actingAs(User::factory()->createQuietly());

    $response = $this->post('/api/products',
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

    $response->assertCreated();
});

it('can not post product with invalid data', function (array $invalidBody) {
    Sanctum::actingAs(User::factory()->createQuietly());

    Product::factory()->createQuietly(['code' => '1']);

    $response = $this->post('/api/products', $invalidBody, ['Accept' => 'application/json']);

    $response->assertUnprocessable();
})->with([[
    ['name' => null, 'expiration_dates' => [['date' => fake()->date('d/m/Y')]]],
    ['name' => 123, 'expiration_dates' => [['date' => fake()->date('d/m/Y')]]],
    ['name' => 'banana', 'code' => null, 'expiration_dates' => [['date' => fake()->date('d/m/Y')]]],
    ['name' => 'banana', 'code' => 123, 'expiration_dates' => [['date' => fake()->date('d/m/Y')]]],
    ['name' => 'banana', 'code' => '1', 'expiration_dates' => [['date' => fake()->date('d/m/Y')]]],
    ['name' => 'banana', 'code' => '1'],
    ['name' => 'banana', 'code' => '1', 'expiration_dates' => []],
    ['name' => 'banana', 'code' => '1', 'expiration_dates' => [['date' => null]]],
    ['name' => 'banana', 'code' => '1', 'expiration_dates' => [['date' => []]]],
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
