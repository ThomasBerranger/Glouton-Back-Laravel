<?php

use App\Models\Product;
use App\Models\ProductRecipe;
use App\Models\Recipe;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

uses()->group('product_recipe');

it('can not delete productsRecipes if no authenticate', function () {
    $response = $this->delete('/api/products_recipes/1', [], ['Accept' => 'application/json']);

    $response->assertUnauthorized();
    $response->assertSee(['message' => 'Unauthenticated.']);
});

it('can not delete productsRecipes not related to current user', function () {
    $productRecipe = ProductRecipe::factory()->createQuietly();

    Sanctum::actingAs(User::factory()->createQuietly());

    $response = $this->delete('/api/products_recipes/' . $productRecipe->id, [], ['Accept' => 'application/json']);

    $response->assertNotFound();
});

it('can delete productsRecipes related to current user', function () {
    $user = User::factory()->createQuietly();

    Sanctum::actingAs($user);

    $productRecipe = ProductRecipe::factory(
        [
            'recipe_id' => Recipe::factory([
                'user_id' => $user,
            ]),
            'product_id' => Product::factory([
                'user_id' => $user,
            ]),
        ]
    )->createQuietly();

    $response = $this->delete('/api/products_recipes/' . $productRecipe->id, ['Accept' => 'application/json']);

    $response->assertNoContent();
    $this->assertModelMissing($productRecipe);
});

it('can delete productsRecipes related to current user but not recipe and product', function () {
    $user = User::factory()->createQuietly();

    // todo: finish this test

    //    Sanctum::actingAs($user);

    //    dump('user : ' . User::count());

    //    $product = Product::factory()->for($user)->createQuietly();

    //    $productRecipe = ProductRecipe::factory()->for($product)->createQuietly();

    //    dd('user : ' . User::count(), 'recipe : ' . Product::count(), Product::all()->toArray()); todo: multiple products and recipe created

    //    $productRecipe = ProductRecipe::factory()
    //        ->forProduct(['user_id' => $user])
    //        ->forRecipe(['user_id' => $user->id])
    //        ->createQuietly();

    //    dd('user : ' . User::count(), 'recipe : ' . Recipe::count(), Recipe::all()->toArray());

    //    $this->assertDatabaseCount('products', 1);

    //    $response = $this->delete('/api/products_recipes/' . $productRecipe->id, ['Accept' => 'application/json']);
    //
    //    $response->assertNoContent();
    //    $this->assertModelMissing($productRecipe);
    //
    //    $this->assertDatabaseCount('recipes', 1);
    //    $this->assertDatabaseCount('products', 1);
});
