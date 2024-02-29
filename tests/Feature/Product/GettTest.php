<?php

use App\Actions\Product\CalculateNextCustomCodeAction;
use App\Actions\Product\FindHighestCustomCodeProduct;
use App\Models\ExpirationDate;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Laravel\Sanctum\Sanctum;

uses()->group('product');

it('can not get products list if no authenticate', function () {
    $response = $this->get('/api/products', ['Accept' => 'application/json']);

    $response->assertStatus(401);
});

it('can get products list', function () {
    $user = User::factory()->createQuietly();

    $products = Product::factory(4)->createQuietly(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    $response = $this->get('/api/products', ['Accept' => 'application/json']);

    $response->assertStatus(200);
    $response->assertJsonCount(4, 'data');
    $response->assertSeeInOrder($products->pluck('name')->toArray());
});

it('can not get other users products', function () {
    Product::factory(4)->createQuietly();

    Sanctum::actingAs(User::factory()->createQuietly());

    $response = $this->get('/api/products', ['Accept' => 'application/json']);

    $response->assertStatus(200);
    $response->assertJsonCount(0, 'data');
});

it('can get products list filtered by week', function () {
    $user = User::factory()->createQuietly();

    Product::factory(['user_id' => $user->id, 'finished_at' => null])
        ->has(ExpirationDate::factory(['date' => Carbon::now()->addDays(2)->format('d/m/Y')]))
        ->createQuietly();

    Product::factory(['user_id' => $user->id, 'finished_at' => null])
        ->has(ExpirationDate::factory(['date' => Carbon::now()->addYear()->format('d/m/Y')]))
        ->createQuietly();

    Sanctum::actingAs($user);

    $response = $this->get('/api/products?filter[category]=week', ['Accept' => 'application/json']);

    $response->assertStatus(200);
    $response->assertJsonCount(1, 'data');
});

it('can get products list filtered by month', function () {
    $user = User::factory()->createQuietly();

    Product::factory(['user_id' => $user->id, 'finished_at' => null])
        ->has(ExpirationDate::factory(['date' => Carbon::now()->format('d/m/Y')]))
        ->createQuietly();

    Product::factory(['user_id' => $user->id, 'finished_at' => null])
        ->has(ExpirationDate::factory(['date' => Carbon::now()->addWeeks(3)->format('d/m/Y')]))
        ->createQuietly();

    Product::factory(['user_id' => $user->id, 'finished_at' => null])
        ->has(ExpirationDate::factory(['date' => Carbon::now()->addYear()->format('d/m/Y')]))
        ->createQuietly();

    Sanctum::actingAs($user);

    $response = $this->get('/api/products?filter[category]=month', ['Accept' => 'application/json']);

    $response->assertStatus(200);
    $response->assertJsonCount(1, 'data');
});

it('can get products list filtered by years', function () {
    $user = User::factory()->createQuietly();

    Product::factory(['user_id' => $user->id, 'finished_at' => null])
        ->has(ExpirationDate::factory(['date' => Carbon::now()->format('d/m/Y')]))
        ->createQuietly();

    Product::factory(['user_id' => $user->id, 'finished_at' => null])
        ->has(ExpirationDate::factory(['date' => Carbon::now()->addYear()->format('d/m/Y')]))
        ->createQuietly();

    Sanctum::actingAs($user);

    $response = $this->get('/api/products?filter[category]=years', ['Accept' => 'application/json']);

    $response->assertStatus(200);
    $response->assertJsonCount(1, 'data');
});

it('can get products list filtered by finished', function () {
    $user = User::factory()->createQuietly();

    Product::factory(['user_id' => $user->id, 'finished_at' => null])->createQuietly();

    Product::factory(['user_id' => $user->id, 'finished_at' => '01/01/2024'])->createQuietly();

    Sanctum::actingAs($user);

    $response = $this->get('/api/products?filter[category]=finished', ['Accept' => 'application/json']);

    $response->assertStatus(200);
    $response->assertJsonCount(1, 'data');
});

it('can get products list filtered by to_purchase', function () {
    $user = User::factory()->createQuietly();

    Product::factory(['user_id' => $user->id, 'added_to_purchase_list_at' => null])->createQuietly();

    Product::factory(['user_id' => $user->id, 'added_to_purchase_list_at' => '01/01/2024'])->createQuietly();

    Sanctum::actingAs($user);

    $response = $this->get('/api/products?filter[category]=to_purchase', ['Accept' => 'application/json']);

    $response->assertStatus(200);
    $response->assertJsonCount(1, 'data');
});

it('can get products list filtered with wrong filter', function () {
    Sanctum::actingAs(User::factory()->createQuietly());

    $response = $this->get('/api/products?filter[wrong]', ['Accept' => 'application/json']);

    $response->assertStatus(400);
    $response->assertJson(['error' => 'Filter unknown']);
});

it('can get products list filtered with wrong filter value', function () {
    Sanctum::actingAs(User::factory()->createQuietly());

    $response = $this->get('/api/products?filter[category]=wrong', ['Accept' => 'application/json']);

    $response->assertStatus(400);
    $response->assertJson(['error' => 'Category value unknown']);
});
