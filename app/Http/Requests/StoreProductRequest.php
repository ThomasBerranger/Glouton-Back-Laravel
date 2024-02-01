<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
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
            'name' => 'required|string|max:255',
            'code' => 'required|integer|min:0|unique:'.Product::class,

            'expiration_dates' => 'required|array|min:1',
            'expiration_dates.*.date' => StoreExpirationDateRequest::dateRules(),

            'description' => 'string',
            'image' => 'string',
            'nutriscore' => 'string|size:1',
            'novagroup' => 'integer|max:4',
            'ecoscore' => 'string|size:1',
            'finished_at' => 'date_format:d/m/Y H:i',
            'added_to_purchase_list_at' => 'date_format:d/m/Y H:i',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'expiration_dates.*' => 'At least one expiration date is required.',
        ];
    }
}
