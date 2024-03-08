<?php

use App\Actions\Product\CalculateNextCustomCodeAction;
use App\Models\Product;

uses()->group('product');

it('can get the next custom code product', function (?string $highestCustomCode, string $nextCustomCodeExpected) {
    if ($highestCustomCode) {
        $product = new Product;

        $product->code = $highestCustomCode;

        expect(resolve(CalculateNextCustomCodeAction::class)($product))->toBeString()->toBe($nextCustomCodeExpected);
    } else {
        expect(resolve(CalculateNextCustomCodeAction::class)(null))->toBeString()->toBe($nextCustomCodeExpected);
    }
})->with([
    [null, Product::CUSTOM_CODE_PREFIX . 1],
    [Product::CUSTOM_CODE_PREFIX . 0, Product::CUSTOM_CODE_PREFIX . 1],
    [Product::CUSTOM_CODE_PREFIX . 354, Product::CUSTOM_CODE_PREFIX . 355],
    [Product::CUSTOM_CODE_PREFIX . 6584, Product::CUSTOM_CODE_PREFIX . 6585],
    [Product::CUSTOM_CODE_PREFIX . 32354898, Product::CUSTOM_CODE_PREFIX . 32354899],
]);
