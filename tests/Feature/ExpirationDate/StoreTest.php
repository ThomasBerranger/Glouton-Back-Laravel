<?php

use App\Models\Product;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

uses()->group('expiration_date');

it('can not post expiration date if no authenticate', function () {
    $response = $this->post('/api/expiration_dates', [], ['Accept' => 'application/json']);

    $response->assertUnauthorized();
    $response->assertSee(['message' => 'Unauthenticated.']);
});

it('can post expiration date related to unknown product', function () {
    $user = User::factory()->createQuietly();

    Sanctum::actingAs($user);

    $response = $this->post('/api/expiration_dates', ['product_id' => '1', 'date' => '01/01/2024'], ['Accept' => 'application/json']);

    $response->assertUnprocessable();
    $response->assertSee(['message' => 'The selected product id is invalid.']);
});

//it('can post product related to current user', function () {
//    $user = User::factory()->createQuietly();
//
//    Sanctum::actingAs($user);
//
//    $response = $this->post('/api/products',
//        [
//            'name' => fake()->name,
//            'expiration_dates' => [
//                ['date' => fake()->date('d/m/Y')],
//            ],
//        ],
//        ['Accept' => 'application/json']);
//
//    $response->assertCreated();
//
//    $this->assertEquals(1, $user->products()->count());
//});
//
//it('can post product', function () {
//    Sanctum::actingAs(User::factory()->createQuietly());
//
//    $response = $this->post('/api/products',
//        [
//            'name' => fake()->name,
//            'code' => fake()->randomElement([
//                (string) fake()->randomNumber(),
//                fake()->numerify(Product::CUSTOM_CODE_PREFIX . '###'),
//            ]),
//            'description' => fake()->sentence,
//            'image' => fake()->url,
//            'nutriscore' => fake()->randomElement(['a', 'b', 'c', 'd', 'e']),
//            'novagroup' => fake()->numberBetween(1, 3),
//            'ecoscore' => fake()->randomElement(['a', 'b', 'c', 'd', 'e']),
//            'finished_at' => fake()->dateTimeBetween('-1 month', '+1 year')->format('d/m/Y'),
//            'added_to_purchase_list_at' => fake()->dateTimeBetween('-1 month', '+1 year')->format('d/m/Y'),
//            'expiration_dates' => [
//                ['date' => fake()->date('d/m/Y')],
//                ['date' => fake()->date('d/m/Y')],
//            ],
//        ],
//        ['Accept' => 'application/json']);
//
//    $response->assertCreated();
//});
//
//it('can not post product with invalid data', function (array $invalidBody) {
//    Sanctum::actingAs(User::factory()->createQuietly());
//
//    Product::factory()->createQuietly(['code' => '1']);
//
//    $response = $this->post('/api/products', $invalidBody, ['Accept' => 'application/json']);
//
//    $response->assertUnprocessable();
//})->with([[
//    ['name' => null, 'expiration_dates' => [['date' => fake()->date('d/m/Y')]]],
//    ['name' => 123, 'expiration_dates' => [['date' => fake()->date('d/m/Y')]]],
//]]);
