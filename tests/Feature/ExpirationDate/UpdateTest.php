<?php

use App\Models\Product;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

uses()->group('expiration_date');

it('can not update expiration date if no authenticate', function () {
    $response = $this->patch('/api/expiration_dates/1', [], ['Accept' => 'application/json']);

    $response->assertUnauthorized();
    $response->assertSee(['message' => 'Unauthenticated.']);
});

it('can not update expiration date not related to current user products', function () {
    Sanctum::actingAs(User::factory()->createQuietly());

    $product = Product::factory()->hasExpirationDates()->createQuietly();

    $response = $this->patch('/api/expiration_dates/' . $product->latestExpirationDate->id, [], ['Accept' => 'application/json']);

    $response->assertNotFound();
});

it('can update product related to current user products', function () {
    $user = User::factory()->createQuietly();

    Sanctum::actingAs($user);

    $product = Product::factory(['user_id' => $user->id])->hasExpirationDates()->createQuietly();

    $response = $this->patch('/api/expiration_dates/' . $product->latestExpirationDate->id,
        [
            'date' => fake()->date('d/m/Y'),
        ],
        ['Accept' => 'application/json']);

    $response->assertOk();
});

it('can not update expiration date with unexpected data', function () {
    $user = User::factory()->createQuietly();

    Sanctum::actingAs($user);

    $product = Product::factory(['user_id' => $user->id])->hasExpirationDates()->createQuietly();

    $this->patch('/api/expiration_dates/' . $product->latestExpirationDate->id,
        [
            'product_id' => '666',
            'date' => '01/04/2025',
        ],
        ['Accept' => 'application/json']
    );

    $this->assertDatabaseHas('expiration_dates', ['product_id' => $product->latestExpirationDate->id, 'date' => '2025-04-01']);
    $this->assertDatabaseMissing('products', ['product_id' => '666', 'date' => '01/04/2025']);
});

it('can not update expiration date with invalid data', function (array $invalidBody) {
    $user = User::factory()->createQuietly();

    Sanctum::actingAs($user);

    $product = Product::factory(['user_id' => $user->id])->hasExpirationDates()->createQuietly();

    $response = $this->patch('/api/expiration_dates/' . $product->latestExpirationDate->id, $invalidBody, ['Accept' => 'application/json']);

    $response->assertUnprocessable();
})->with([[
    ['date' => null],
    ['date' => 1],
    ['date' => ''],
    ['date' => fake()->date('d-m-Y')],
    ['date' => fake()->date('Y/m/d')],
]]);
