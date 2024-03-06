<?php

use App\Models\Product;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

uses()->group('product');

it('can not delete product if no authenticate', function () {
    $response = $this->delete('/api/products/1', [], ['Accept' => 'application/json']);

    $response->assertStatus(401);
});

it('can not delete product not related to current user', function () {
    Sanctum::actingAs(User::factory()->createQuietly());

    $product = Product::factory()->createQuietly();

    $response = $this->delete('/api/products/' . $product->id, [], ['Accept' => 'application/json']);

    $response->assertStatus(404);
});

it('can delete product related to current user', function () {
    $user = User::factory()->createQuietly();

    Sanctum::actingAs($user);

    $product = Product::factory(['user_id' => $user->id])->createQuietly();

    $response = $this->delete('/api/products/' . $product->id, ['Accept' => 'application/json']);

    $response->assertNoContent();
    $this->assertModelMissing($product);
});
