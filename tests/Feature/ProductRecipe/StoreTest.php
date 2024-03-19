<?php

use App\Models\Product;
use App\Models\Recipe;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

uses()->group('product_recipe');

it('can not post productRecipe if no authenticate', function () {
    $response = $this->post('/api/products_recipes', [], ['Accept' => 'application/json']);

    $response->assertUnauthorized();
    $response->assertSee(['message' => 'Unauthenticated.']);
});

it('can post productRecipe', function () {
    $user = User::factory()->createQuietly();

    Sanctum::actingAs($user);

    $product = Product::factory()->createQuietly(['user_id' => $user]);
    $recipe = Recipe::factory()->createQuietly(['user_id' => $user]);

    $response = $this->post('/api/products_recipes',
        [
            'product_id' => (string) $product->id,
            'recipe_id' => (string) $recipe->id,
            'quantity' => fake()->randomFloat(2),
            'quantity_unity' => fake()->word,
        ],
        ['Accept' => 'application/json']);

    $response->assertCreated();
});

it('can not post productRecipe if current user not related to product', function () {
    $user = User::factory()->createQuietly();

    Sanctum::actingAs($user);

    $product = Product::factory()->createQuietly();
    $recipe = Recipe::factory()->createQuietly(['user_id' => $user]);

    $body = [
        'product_id' => (string) $product->id,
        'recipe_id' => (string) $recipe->id,
        'quantity' => fake()->randomFloat(2),
        'quantity_unity' => fake()->word,
    ];

    $response = $this->post('/api/products_recipes', $body, ['Accept' => 'application/json']);

    $response->assertNotFound();
});

it('can not post productRecipe if current user not related to recipe', function () {
    $user = User::factory()->createQuietly();

    Sanctum::actingAs($user);

    $product = Product::factory()->createQuietly(['user_id' => $user]);
    $recipe = Recipe::factory()->createQuietly();

    $body = [
        'product_id' => (string) $product->id,
        'recipe_id' => (string) $recipe->id,
        'quantity' => fake()->randomFloat(2),
        'quantity_unity' => fake()->word,
    ];

    $response = $this->post('/api/products_recipes', $body, ['Accept' => 'application/json']);

    $response->assertNotFound();
});

it('can not post productRecipe with invalid data', function (array $invalidBody) {
    $user = User::factory()->createQuietly();

    Sanctum::actingAs($user);

    Product::factory()->createQuietly(['user_id' => $user]);
    Recipe::factory()->createQuietly(['user_id' => $user]);

    $response = $this->post('/api/products_recipes', $invalidBody, ['Accept' => 'application/json']);

    $response->assertUnprocessable();
})->with([[
    ['product_id' => null, 'recipe_id' => '1', 'quantity' => '10', 'quantity_unity' => 'grammes'],
    ['product_id' => 1, 'recipe_id' => '1', 'quantity' => '10', 'quantity_unity' => 'grammes'],
    ['product_id' => '1', 'recipe_id' => null, 'quantity' => '10', 'quantity_unity' => 'grammes'],
    ['product_id' => '1', 'recipe_id' => 1, 'quantity' => '10', 'quantity_unity' => 'grammes'],
    ['product_id' => '1', 'recipe_id' => '1', 'quantity' => null, 'quantity_unity' => 'grammes'],
    ['product_id' => '1', 'recipe_id' => '1', 'quantity' => 10.123, 'quantity_unity' => 'grammes'],
    ['product_id' => '1', 'recipe_id' => '1', 'quantity' => -1, 'quantity_unity' => 'grammes'],
    ['product_id' => '1', 'recipe_id' => '1', 'quantity' => '10', 'quantity_unity' => null],
]]);
