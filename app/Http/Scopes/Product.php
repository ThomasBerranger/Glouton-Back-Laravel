<?php

namespace App\Http\Scopes;

use Carbon\Carbon;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

trait Product
{
    public function scopeNotFinished($query): void
    {
        $query->whereNull('finished_at');
    }

    public function scopeWeek($query): void
    {
        $query
            ->select(
                'products.*',
                DB::raw('MIN(expiration_dates.date) AS closest_expiration_date'),
                DB::raw('(SELECT COUNT(*) FROM expiration_dates WHERE expiration_dates.product_id = products.id) AS expiration_date_count')
            )
            ->join('expiration_dates', function (JoinClause $join) {
                $join->on('products.id', '=', 'expiration_dates.product_id')
                    ->where('expiration_dates.date', '<=', Carbon::now()->addWeek()->format('Y-m-d'));
            })
            ->groupBy('products.id')
            ->orderBy('closest_expiration_date');
    }

    public function scopeMonth($query): void
    {
        $query
            ->select(
                'products.*',
                DB::raw('MIN(expiration_dates.date) AS closest_expiration_date'),
                DB::raw('(SELECT COUNT(*) FROM expiration_dates WHERE expiration_dates.product_id = products.id) AS expiration_date_count')
            )
            ->join('expiration_dates', function (JoinClause $join) {
                $join->on('products.id', '=', 'expiration_dates.product_id')
                    ->where('expiration_dates.date', '>', Carbon::now()->addWeek()->format('Y-m-d'))
                    ->where('expiration_dates.date', '<=', Carbon::now()->addMonth()->format('Y-m-d'));
            })
            ->groupBy('products.id')
            ->orderBy('closest_expiration_date');
    }

    public function scopeYears($query): void
    {
        $query
            ->select(
                'products.*',
                DB::raw('MIN(expiration_dates.date) AS closest_expiration_date'),
                DB::raw('(SELECT COUNT(*) FROM expiration_dates WHERE expiration_dates.product_id = products.id) AS expiration_date_count')
            )
            ->join('expiration_dates', function (JoinClause $join) {
                $join->on('products.id', '=', 'expiration_dates.product_id')
                    ->where('expiration_dates.date', '>', Carbon::now()->addMonth()->format('Y-m-d'));
            })
            ->groupBy('products.id')
            ->orderBy('closest_expiration_date');
    }

    public function scopeFinished($query): void
    {
        $query->whereNotNull('finished_at');
    }

    public function scopeToPurchase($query): void
    {
        $query->whereNotNull('added_to_purchase_list_at');
    }

    public function scopeOrderedBy($query, string $column, bool $ascending = true): void
    {
        $query->orderBy($column, $ascending ? 'asc' : 'desc');
    }

    public function scopeGroupedByMinExpirationDate($query): void
    {
        $query
            ->select(
                'products.*',
                DB::raw('MIN(expiration_dates.date) AS closest_expiration_date'),
                DB::raw('COUNT(expiration_dates.product_id) AS expiration_date_count')
            )
            ->leftJoin('expiration_dates', 'products.id', '=', 'expiration_dates.product_id')
            ->groupBy('products.id');
    }
}
