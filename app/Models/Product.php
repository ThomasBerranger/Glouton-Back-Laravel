<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'user_id',
        'expiration_dates',
        'description',
        'image',
        'nutriscore',
        'novagroup',
        'ecoscore',
        'finished_at',
        'added_to_purchase_list_at',
    ];

    protected $casts = [
        'expiration_dates' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function finishedAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::make($value)->format('d/m/Y H:i'),
            set: fn (string $value) => Carbon::createFromFormat('d/m/Y H:i', $value),
        );
    }
}
