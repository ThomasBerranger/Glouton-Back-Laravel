<?php

namespace App\Observers;

use App\Actions\Product\FindHighestCustomCodeProduct;
use App\Actions\Product\CalculateNextCustomCodeAction;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductObserver
{
    public function creating(Product $product): void
    {
        if (!$product->code) {
            $highestCustomCodeProduct = resolve(FindHighestCustomCodeProduct::class)();
            $product->code = resolve(CalculateNextCustomCodeAction::class)($highestCustomCodeProduct);
        }

        $product->user()->associate(Auth::user());
    }

    public function created(Product $product): void
    {
        //
    }

    public function updated(Product $product): void
    {
        //
    }

    public function deleted(Product $product): void
    {
        //
    }

    public function restored(Product $product): void
    {
        //
    }

    public function forceDeleted(Product $product): void
    {
        //
    }
}
