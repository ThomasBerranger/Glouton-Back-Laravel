<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

class DateAttributeUtil
{
    public static function dateAttribute(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value): ?string => $value && is_string($value) ? Carbon::parse($value)->format('d/m/Y') : null,
            set: function (?string $value): ?string {
                if ($value && $date = Carbon::createFromFormat('d/m/Y', $value)) {
                    return $date->format('Y-m-d');
                }

                return null;
            }
        );
    }
}
