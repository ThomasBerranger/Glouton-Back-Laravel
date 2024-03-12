<?php

use App\Models\Product;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

uses()->group('expiration_date');

it('can not delete expiration date if no authenticate', function () {
    $response = $this->delete('/api/expiration_dates/1', [], ['Accept' => 'application/json']);

    $response->assertUnauthorized();
    $response->assertSee(['message' => 'Unauthenticated.']);
});

it('can not delete expiration date not related to current user products', function () {
    Sanctum::actingAs(User::factory()->createQuietly());

    $product = Product::factory()->hasExpirationDates()->createQuietly();

    $response = $this->delete('/api/expiration_dates/' . $product->expirationDates->first()->id, [], ['Accept' => 'application/json']);

    $response->assertNotFound();
});

it('can delete product related to current user', function () {
    $user = User::factory()->createQuietly();

    Sanctum::actingAs($user);

    $product = Product::factory()->hasExpirationDates()->createQuietly(['user_id' => $user->id]);

    $expirationDate = $product->expirationDates->first();

    $response = $this->delete('/api/expiration_dates/' . $expirationDate->id, [], ['Accept' => 'application/json']);

    $response->assertNoContent();
    $this->assertModelMissing($expirationDate);
});
