<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

uses()->group('recipe');

it('can not post recipe if no authenticate', function () {
    $response = $this->post('/api/recipes', [], ['Accept' => 'application/json']);

    $response->assertUnauthorized();
    $response->assertSee(['message' => 'Unauthenticated.']);
});

it('can post recipe related to current user', function () {
    $user = User::factory()->createQuietly();

    Sanctum::actingAs($user);

    $response = $this->post('/api/recipes',
        [
            'name' => fake()->word,
        ],
        ['Accept' => 'application/json']);

    $response->assertCreated();

    $this->assertEquals(1, $user->recipes()->count());
});

it('can post recipe', function () {
    Sanctum::actingAs(User::factory()->createQuietly());

    $response = $this->post('/api/recipes',
        [
            'name' => fake()->word,
            'description' => fake()->sentence,
        ],
        ['Accept' => 'application/json']);

    $response->assertCreated();
});

it('can not post recipe with invalid data', function (array $invalidBody) {
    Sanctum::actingAs(User::factory()->createQuietly());

    $response = $this->post('/api/recipes', $invalidBody, ['Accept' => 'application/json']);

    $response->assertUnprocessable();
})->with([[
    ['name' => null, 'description' => 'description'],
    ['name' => 123, 'description' => 'description'],
    ['name' => '', 'description' => 'description'],
    ['name' => 'Banana', 'description' => null],
    ['name' => 'Banana', 'description' => 123],
]]);
