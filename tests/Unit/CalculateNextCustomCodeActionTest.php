<?php

use App\Actions\Product\CalculateNextCustomCodeAction;
use App\Models\Product;

uses()->group('product');

it('can get the next product custom code', function (string $latestCustomCode, string $expectedCustomCode) {
    $product = Product::factory()->makeOne(['code' => $latestCustomCode]);

    expect(resolve(CalculateNextCustomCodeAction::class)($product))->toBeString()->toBe($expectedCustomCode);
})->with([
    [Product::CUSTOM_CODE_PREFIX . 0, Product::CUSTOM_CODE_PREFIX . 1],
    [Product::CUSTOM_CODE_PREFIX . 354, Product::CUSTOM_CODE_PREFIX . 355],
    [Product::CUSTOM_CODE_PREFIX . 6584, Product::CUSTOM_CODE_PREFIX . 6585],
    [Product::CUSTOM_CODE_PREFIX . 32354898, Product::CUSTOM_CODE_PREFIX . 32354899],
]);
