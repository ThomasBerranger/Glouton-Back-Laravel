<?php

use App\Models\Recipe;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

uses()->group('recipe');

it('can not delete recipe if no authenticate', function () {
    $response = $this->delete('/api/recipes/1', [], ['Accept' => 'application/json']);

    $response->assertUnauthorized();
    $response->assertSee(['message' => 'Unauthenticated.']);
});

it('can not delete recipe not related to current user', function () {
    Sanctum::actingAs(User::factory()->createQuietly());

    $recipe = Recipe::factory()->createQuietly();

    $response = $this->delete('/api/recipes/' . $recipe->id, [], ['Accept' => 'application/json']);

    $response->assertNotFound();
});

it('can delete recipe related to current user', function () {
    $user = User::factory()->createQuietly();

    Sanctum::actingAs($user);

    $recipe = Recipe::factory(['user_id' => $user->id])->createQuietly();

    $response = $this->delete('/api/recipes/' . $recipe->id, ['Accept' => 'application/json']);

    $response->assertNoContent();
    $this->assertModelMissing($recipe);
});
