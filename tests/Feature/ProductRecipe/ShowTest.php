<?php

use App\Models\ExpirationDate;
use App\Models\Product;
use App\Models\ProductRecipe;
use App\Models\Recipe;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

uses()->group('product_recipe');

it('can not get productRecipe if no authenticate', function () {
    $response = $this->get('/api/products_recipes/1', ['Accept' => 'application/json']);

    $response->assertUnauthorized();
    $response->assertSee(['message' => 'Unauthenticated.']);
});

it('can get productRecipe', function () {
    $user = User::factory()->createQuietly();

    Sanctum::actingAs($user);

    $productRecipe = ProductRecipe::factory(
        [
            'recipe_id' => Recipe::factory([
                'user_id' => $user
            ]),
            'product_id' => Product::factory([
                'user_id' => $user
            ])
        ]
    )->createQuietly();

//    dd($user->id, $productRecipe->user()->get()->id);

    $response = $this->get('/api/products_recipes/' . $productRecipe->id, ['Accept' => 'application/json']);

    $response->assertOk();
});

//it('can get productRecipe expected fields', function () {
//    Sanctum::actingAs(User::factory()->createQuietly());
//
//    $productRecipe = ProductRecipe::factory()->createQuietly();
//
//    $response = $this->get('/api/products_recipes/' . $productRecipe->id, ['Accept' => 'application/json']);
//
//    $response->assertExactJson([
//        'data' => [
//            'id' => $productRecipe->id,
//            'quantity' => $productRecipe->quantity,
//            'quantity_unity' => $productRecipe->quantity_unity,
//        ],
//    ]);
//});

//it('can not get other users productsRecipes', function () {
//    $product = Product::factory()->createQuietly();
//
//    Sanctum::actingAs(User::factory()->createQuietly());
//
//    $response = $this->get('/api/products/' . $product->id, ['Accept' => 'application/json']);
//
//    $response->assertNotFound();
//});
