<?php

use App\Http\Controllers\Authentication\AuthenticationController;
use App\Http\Controllers\Authentication\RegisterController;
use App\Http\Controllers\ExpirationDateController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RecipeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * API Routes
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 */
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [RegisterController::class, 'store'])
    ->middleware('guest')
    ->name('register');

Route::controller(AuthenticationController::class)->group(function () {
    Route::post('login', 'store')->middleware('guest');
    Route::post('logout', 'destroy')->middleware('auth:sanctum');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(ProductController::class)->group(function () {
        Route::get('products', 'index');
        Route::get('products/{product}', 'show');
        Route::post('products', 'store');
        Route::patch('products/{product}', 'update');
        Route::delete('products/{product}', 'destroy');
    });
    Route::controller(ExpirationDateController::class)->group(function () {
        Route::post('expiration_dates', 'store');
        Route::patch('expiration_dates/{expirationDate}', 'update');
        Route::delete('expiration_dates/{expirationDate}', 'destroy');
    });
    Route::controller(RecipeController::class)->group(function () {
        Route::get('recipes', 'index');
        Route::post('recipes', 'store');
    });
});
