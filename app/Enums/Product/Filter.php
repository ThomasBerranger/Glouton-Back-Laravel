<?php

namespace App\Enums\Product;

enum Filter: string
{
    case WEEK = 'week';
    case MONTH = 'month';
    case YEARS = 'years';
    case FINISHED = 'finished';
    case TO_PURCHASE = 'to_purchase';
}
