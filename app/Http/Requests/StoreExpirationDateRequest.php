<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreExpirationDateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public static function dateRules(): string
    {
        return 'required|date_format:d/m/Y';
    }

    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:' . Product::class . ',id',
            'date' => self::dateRules()
        ];
    }
}
