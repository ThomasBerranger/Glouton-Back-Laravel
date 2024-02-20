<?php

namespace App\Actions\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;

class CalculateNextCustomCodeAction extends Controller
{
    public function __invoke(?Product $latestCustomCodeProduct): string
    {
        $lastCustomCode = $latestCustomCodeProduct ? $latestCustomCodeProduct->code : Product::CUSTOM_CODE_PREFIX . 0;

        $lastCustomCodeNumber = (int) str_replace(Product::CUSTOM_CODE_PREFIX, '', $lastCustomCode);

        return Product::CUSTOM_CODE_PREFIX . ($lastCustomCodeNumber + 1);
    }
}
