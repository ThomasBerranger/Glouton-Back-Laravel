<?php

namespace App\Http\Scopes;

trait Product
{
    public function scopeOrderedByClosestExpirationDate($query): void
    {
        $query
            ->select('products.*')
            ->join('expiration_dates', 'products.id', '=', 'expiration_dates.product_id')
            ->groupBy('products.id')
            ->orderBy('expiration_dates.date')
        ;
    }
}
