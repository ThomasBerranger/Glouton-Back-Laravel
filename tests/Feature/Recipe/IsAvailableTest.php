<?php

use App\Models\Product;
use App\Models\Recipe;
use Carbon\Carbon;

uses()->group('test');

it('determine a recipe available with product not finished', function () {
    $recipe = Recipe::factory()->createQuietly();

    $products = Product::factory(3)->createQuietly(['finished_at' => null]);

    $recipe->products()->syncWithPivotValues($products->pluck('id')->toArray(), ['quantity' => fake()->numberBetween(0, 100)]);

    expect($recipe->isAvailable())->toBeTrue();
});

it('determine a recipe unavailable with no product', function () {
    $recipe = Recipe::factory()->createQuietly();

    expect($recipe->isAvailable())->toBeFalse();
});

it('determine a recipe unavailable with at least one product finished', function () {
    $recipe = Recipe::factory()->createQuietly();

    $product = Product::factory()->createQuietly(['finished_at' => Carbon::now()->format('d/m/Y')]);

    $recipe->products()->attach($product->id, ['quantity' => fake()->numberBetween(0, 100)]);

    expect($recipe->isAvailable())->toBeFalse();
});
