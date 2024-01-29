<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpirationDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'date',
    ];

    protected $casts = [
        'date' => 'date:d/m/Y',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function date(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value): ?string => Carbon::parse($value)->format('d/m/Y'),
            set: fn (mixed $value): ?string => Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d'),
        );
    }
}
