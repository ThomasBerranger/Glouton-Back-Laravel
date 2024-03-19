<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRecipeRequest extends FormRequest
{
    public function authorize(): true
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|string|exists:' . Product::class . ',id',
            'recipe_id' => 'required|string|exists:' . Recipe::class . ',id',
            'quantity' => 'decimal:0,2|min:0',
            'quantity_unity' => 'string',
        ];
    }
}
