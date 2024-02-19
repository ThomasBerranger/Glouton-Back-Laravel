<?php

uses()->group('authentication');

use App\Models\User;

//test('users can authenticate using their token', function () {
//    $user = User::factory()->create();
//
//    $response = $this->post('/login', [
//        'email' => $user->email,
//        'password' => 'password',
//    ]);
//
//    $this->assertAuthenticated();
//    $response->assertNoContent();
//});

//test('users can not authenticate with invalid password', function () {
//    $user = User::factory()->create();
//
//    $this->post('/login', [
//        'email' => $user->email,
//        'password' => 'wrong-password',
//    ]);
//
//    $this->assertGuest();
//});

//test('users can logout', function () {
//    $user = User::factory()->create();
//
//    $response = $this->actingAs($user)->post('/logout');
//
//    $response->assertNoContent();
//});


//it('can return a token for a registered user', function () {
//    $this->post('/api/login', [
//        'email' => $this->user->email,
//        'password' => UserFactory::PASSWORD,
//    ])->assertCreated()->assertJson(function (AssertableJson $json) {
//        $json->whereType('token', 'string');
//    });
//});
//
//it('can create in database a token for a registered user', function () {
//    $this->post('/api/login', [
//        'email' => $this->user->email,
//        'password' => UserFactory::PASSWORD,
//    ]);
//
//    $this->assertDatabaseCount('personal_access_tokens', 1);
//});

//it('can create a token which expires in two weeks', function () {
//    $this->post('/api/login', [
//        'email' => $this->user->email,
//        'password' => $this->user->password,
//    ]);
//
//    $this->travelTo(now()->addWeeks(2)->addDay()->addSecond());
//
//    $this->artisan('sanctum:prune-expired --hours=24')->execute();
//
//    $this->assertDatabaseEmpty('personal_access_tokens');
//});

//it('can\'t create a token for an unregistered user', function () {
//    $this->post('/api/login',
//        [
//            'email' => fake()->email,
//            'password' => fake()->password,
//        ],
//        ['Accept' => 'application/json']
//    )->assertUnprocessable();
//});

//it('can\'t create a token for a user with wrong password', function () {
//    $this->post('/api/login',
//        [
//            'email' => $this->user->email,
//            'password' => 'wrong password',
//        ],
//        ['Accept' => 'application/json']
//    )->assertUnprocessable();
//});
