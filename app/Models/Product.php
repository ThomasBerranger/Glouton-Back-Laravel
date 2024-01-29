<?php

namespace App\Models;

use App\Models\Scopes\User as UserScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, UserScope;

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
            get: fn(?string $value) => $value ? Carbon::make($value)->format('d/m/Y H:i') : null,
            set: fn(?string $value) => $value ? Carbon::createFromFormat('d/m/Y H:i', $value) : null,
        );
    }
}
