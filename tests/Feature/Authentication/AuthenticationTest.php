<?php

use App\Models\User;
use Tests\TestCase;

uses()->group('authentication');

it('users can authenticate using their token', function () {
    $user = User::factory()->create();

    $response = $this->post('/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertDatabaseCount('personal_access_tokens', 1);
    $this->assertDatabaseHas('personal_access_tokens', [
        'tokenable_type' => User::class,
        'name' => 'login',
        'abilities' => '["*"]'
    ]);

    $response->assertHeader('Content-Type', 'application/json');
    $response->assertOk();
    $response->assertJsonStructure(['token']);
});

it('can not authenticate users with invalid password', function () {
    $user = User::factory()->create();

    $response = $this->post(
        '/api/login',
        ['email' => $user->email, 'password' => 'wrong-password'],
        ['Accept' => 'application/json']
    );

    $response->assertUnprocessable();
    $response->assertHeader('Content-Type', 'application/json');
});

it('can logout users', function () {
    $user = User::factory()->withToken(User::LOGIN_TOKEN_NAME)->create();

    $this->assertDatabaseCount('personal_access_tokens', 1);

    $response = $this->actingAs($user)->post('/api/logout');

    $this->assertDatabaseCount('personal_access_tokens', 0);

    $response->assertNoContent();
});
