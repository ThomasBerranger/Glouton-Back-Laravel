<?php

namespace App\Actions;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class GetNextCustomCodeAction extends Controller
{
    public function __invoke(): string
    {
        $lastCustomCode = DB::table('products')->where('code', 'like', Product::CUSTOM_CODE_PREFIX . '%')->get()->last()->code;

        $higherCustomCodeNumber = (int) str_replace(Product::CUSTOM_CODE_PREFIX, '', $lastCustomCode);

        return Product::CUSTOM_CODE_PREFIX . $higherCustomCodeNumber + 1;
    }
}
