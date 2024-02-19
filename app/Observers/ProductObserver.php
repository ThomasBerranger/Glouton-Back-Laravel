<?php

namespace App\Observers;

use App\Actions\GetNextCustomCodeAction;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductObserver
{
    public function creating(Product $product): void
    {
        if (!$product->code) {
            $product->code = resolve(GetNextCustomCodeAction::class)();
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
