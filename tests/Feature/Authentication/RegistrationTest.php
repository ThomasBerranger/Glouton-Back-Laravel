<?php

use App\Models\User;

uses()->group('authentication');

it('can register new user', function () {
    $response = $this->post('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertDatabaseCount('personal_access_tokens', 1);
    $this->assertDatabaseHas('personal_access_tokens', [
        'tokenable_type' => User::class,
        'name' => 'register',
        'abilities' => '["*"]',
    ]);

    $response->assertHeader('Content-Type', 'application/json');
    $response->assertOk();
    $response->assertJsonStructure(['token']);
});

it('can not register new users', function (array $body) {
    $response = $this->post('/api/register', $body, ['Accept' => 'application/json']);

    $response->assertUnprocessable();
    $response->assertHeader('Content-Type', 'application/json');
})->with([
    [['email' => 'test@example.com', 'password' => 'password', 'password_confirmation' => 'password']],
    [['name' => 'test', 'password' => 'password', 'password_confirmation' => 'password']],
    [['name' => 'test', 'email' => 'test@example.com', 'password_confirmation' => 'password']],
    [['name' => 'test', 'email' => 'test@example.com', 'password' => 'password']],
    [['name' => 'test', 'email' => 'test@example.com', 'password' => 'password', 'password_confirmation' => 'wrong password']],
]);
