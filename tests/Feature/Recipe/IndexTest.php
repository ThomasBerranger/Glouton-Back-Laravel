<?php

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

    Recipe::factory(4)->createQuietly(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    $response = $this->get('/api/recipes', ['Accept' => 'application/json']);

    $response->assertExactJson([
        'data' => $user
            ->recipes()
            ->get()
            ->map(function (Recipe $recipe) {
                return [
                    'description' => $recipe->description,
                    'id' => $recipe->id,
                    'name' => $recipe->name,
                ];
            }),
    ]);
});
