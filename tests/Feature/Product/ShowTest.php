<?php

use App\Models\ExpirationDate;
use App\Models\Product;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

uses()->group('product');

it('can not get product if no authenticate', function () {
    $response = $this->get('/api/products/1', ['Accept' => 'application/json']);

    $response->assertStatus(401);
});

it('can get product', function () {
    $user = User::factory()->createQuietly();

    $product = Product::factory()->createQuietly(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    $response = $this->get('/api/products/' . $product->id, ['Accept' => 'application/json']);

    $response->assertStatus(200);
});

it('can get product expected fields', function () {
    $user = User::factory()->createQuietly();

    $product = Product::factory(['user_id' => $user->id])->hasExpirationDates()->createQuietly();

    Sanctum::actingAs($user);

    $response = $this->get('/api/products/' . $product->id, ['Accept' => 'application/json']);

    $response->assertExactJson([
        'data' => [
            'id' => $product->id,
            'name' => $product->name,
            'code' => $product->code,
            'expiration_dates' => $product->expirationDates->map(function (ExpirationDate $expirationDate) {
                return ['date' => $expirationDate->date, 'id' => $expirationDate->id];
            }),
            'description' => $product->description,
            'image' => $product->image,
            'nutriscore' => $product->nutriscore,
            'novagroup' => $product->novagroup,
            'ecoscore' => $product->ecoscore,
            'finished_at' => $product->finished_at,
            'added_to_purchase_list_at' => $product->added_to_purchase_list_at,
            'closest_expiration_date' => $product->closest_expiration_date,
            'expiration_date_count' => $product->expiration_date_count,
        ],
    ]);
});

it('can not get other users products', function () {
    $product = Product::factory()->createQuietly();

    Sanctum::actingAs(User::factory()->createQuietly());

    $response = $this->get('/api/products/' . $product->id, ['Accept' => 'application/json']);

    $response->assertStatus(404);
});
