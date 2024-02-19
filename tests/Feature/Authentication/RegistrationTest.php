<?php

uses()->group('authentication');

test('new users can register', function () {
    $response = $this->post('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertHeader('Content-Type', 'application/json');
    $response->assertOk();
    $response->assertJsonStructure(['token']);
});
