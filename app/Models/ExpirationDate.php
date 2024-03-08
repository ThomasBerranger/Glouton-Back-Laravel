<?php

namespace App\Models;

use App\Utils\DateAttributeUtil;
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

    //    public function product(): BelongsTo
    //    {
    //        return $this->belongsTo(Product::class);
    //    }

    public function date(): Attribute
    {
        return DateAttributeUtil::dateAttribute();
    }
}
