<?php

use App\Models\Product;
use App\Models\Recipe;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

uses()->group('recipe');

it('can not get recipes list if no authenticate', function () {
    $response = $this->get('/api/recipes', ['Accept' => 'application/json']);

    $response->assertUnauthorized();
    $response->assertSee(['message' => 'Unauthenticated.']);
});

it('can get recipes list', function () {
    $user = User::factory()->createQuietly();

    $recipes = Recipe::factory(4)->createQuietly(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    $response = $this->get('/api/recipes', ['Accept' => 'application/json']);

    $response->assertStatus(200);
    $response->assertJsonCount(4, 'data');
    $response->assertSeeInOrder($recipes->pluck('name')->toArray());
});

it('can not get other users recipes', function () {
    Recipe::factory(4)->createQuietly();

    Sanctum::actingAs(User::factory()->createQuietly());

    $response = $this->get('/api/products', ['Accept' => 'application/json']);

    $response->assertOk();
    $response->assertJsonCount(0, 'data');
});

it('can get recipes expected fields', function () {
    $user = User::factory()->createQuietly();

    $product = Product::factory()->createQuietly();
    Recipe::factory(4)->hasProductsRecipes(['product_id' => $product->id])->createQuietly(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    $response = $this->get('/api/recipes', ['Accept' => 'application/json']);

    $response->assertExactJson([
        'data' => $user
            ->recipes()
            ->get()
            ->map(function (Recipe $recipe) {
                return [
                    'available' => $recipe->isAvailable(),
                    'description' => $recipe->description,
                    'id' => $recipe->id,
                    'name' => $recipe->name,
                    'productsRecipes' => $recipe->productsRecipes->map(fn ($productRecipe) => [
                        'id' => $productRecipe->id,
                        'product' => [
                            'added_to_purchase_list_at' => $productRecipe->product->added_to_purchase_list_at,
                            'closest_expiration_date' => $productRecipe->product->closest_expiration_date,
                            'code' => $productRecipe->product->code,
                            'description' => $productRecipe->product->description,
                            'ecoscore' => $productRecipe->product->ecoscore,
                            'expiration_date_count' => $productRecipe->product->expiration_date_count,
                            'finished_at' => $productRecipe->product->finished_at,
                            'id' => $productRecipe->product->id,
                            'image' => $productRecipe->product->image,
                            'name' => $productRecipe->product->name,
                            'novagroup' => $productRecipe->product->novagroup,
                            'nutriscore' => $productRecipe->product->nutriscore,
                        ],
                        'quantity' => $productRecipe->quantity,
                        'quantity_unity' => $productRecipe->quantity_unity,
                    ]),
                    'user' => ['name' => $recipe->user->name],
                ];
            }),
    ]);
});
