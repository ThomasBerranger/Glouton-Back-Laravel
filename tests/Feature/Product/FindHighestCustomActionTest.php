<?php

use App\Actions\Product\CalculateNextCustomCodeAction;
use App\Actions\Product\FindHighestCustomCodeProduct;
use App\Models\Product;

uses()->group('product');

it('find the latest product custom code', function (array $codes, string $expectedCode) {
    foreach ($codes as $code) {
        Product::factory()->createQuietly(['code' => $code]);
    }

    $expectedProduct = Product::firstWhere('code', $expectedCode);

    expect(resolve(FindHighestCustomCodeProduct::class)())->toEqual($expectedProduct);
})->with([
    [[Product::CUSTOM_CODE_PREFIX . '1'], Product::CUSTOM_CODE_PREFIX . '1'],
    [['1'], Product::CUSTOM_CODE_PREFIX . '1'],
    [[Product::CUSTOM_CODE_PREFIX . '1', Product::CUSTOM_CODE_PREFIX . '3', Product::CUSTOM_CODE_PREFIX . '2'], Product::CUSTOM_CODE_PREFIX . '3'],
    [['1', '201350002654', Product::CUSTOM_CODE_PREFIX . '1', Product::CUSTOM_CODE_PREFIX . '2'], Product::CUSTOM_CODE_PREFIX . '2', '32123'],
]);
