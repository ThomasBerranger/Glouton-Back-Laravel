<?php

namespace App\Models;

use App\Http\Scopes\Product as ProductScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, ProductScope;

    protected $guarded = [
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function expirationDates(): HasMany
    {
        return $this->hasMany(ExpirationDate::class);
    }

    protected function finishedAt(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value): ?string => $value ? Carbon::parse($value)->format('d/m/Y H:i:s') : null,
            set: fn (mixed $value): ?string => $value ? Carbon::createFromFormat('d/m/Y H:i:s', $value)->format('Y-m-d H:i:s') : null,
        );
    }

    protected function addedToPurchaseListAt(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value): ?string => $value ? Carbon::parse($value)->format('d/m/Y H:i:s') : null,
            set: fn (mixed $value): ?string => $value ? Carbon::createFromFormat('d/m/Y H:i:s', $value)->format('Y-m-d H:i:s') : null,
        );
    }
}
