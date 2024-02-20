<?php

namespace App\Actions\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;

class FindHighestCustomCodeProduct extends Controller
{
    public function __invoke(): ?Product
    {
        return Product::where('code', 'like', Product::CUSTOM_CODE_PREFIX . '%')->orderBy('code', 'desc')->first();
    }
}
