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

it('can not post expiration date related to unknown product', function () {
    $user = User::factory()->createQuietly();

    Sanctum::actingAs($user);

    $response = $this->post('/api/expiration_dates', ['product_id' => '1', 'date' => '01/01/2024'], ['Accept' => 'application/json']);

    $response->assertNotFound();
    $response->assertSee(['message' => 'Product not found.']);
});

it('can not post expiration date not related to product not related to current user', function () {
    $user = User::factory()->createQuietly();

    $product = Product::factory()->createQuietly(['user_id' => $user->id]);

    Sanctum::actingAs(User::factory()->createQuietly());

    $response = $this->post('/api/expiration_dates', ['product_id' => (string) $product->id, 'date' => '01/01/2024'], ['Accept' => 'application/json']);

    $response->assertNotFound();
    $response->assertSee(['message' => 'Product not found.']);
});

it('can post expiration date', function () {
    $user = User::factory()->createQuietly();

    $product = Product::factory()->createQuietly(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    $response = $this->post('/api/expiration_dates',
        [
            'product_id' => (string) $product->id,
            'date' => fake()->date('d/m/Y'),
        ],
        ['Accept' => 'application/json']
    );

    $response->assertCreated();

    $this->assertEquals(1, $product->expirationDates()->count());
});

it('can not expiration date with invalid data', function (array $invalidBody) {
    $user = User::factory()->createQuietly();

    Product::factory()->createQuietly(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    $response = $this->post('/api/expiration_dates', $invalidBody, ['Accept' => 'application/json']);

    $response->assertUnprocessable();
})->with([[
    ['product_id' => null, 'date' => fake()->date('d/m/Y')],
    ['product_id' => '', 'date' => fake()->date('d/m/Y')],
    ['product_id' => 1, 'date' => fake()->date('d/m/Y')],
    ['product_id' => '1', 'date' => null],
    ['product_id' => '1', 'date' => ''],
    ['product_id' => '1', 'date' => fake()->date('Y-m-d')],
]]);
