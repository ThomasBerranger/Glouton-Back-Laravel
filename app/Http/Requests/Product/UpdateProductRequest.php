<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' => 'string|max:255',
            'description' => 'string',
            'image' => 'string',
            'nutriscore' => 'string|size:1',
            'novagroup' => 'integer|max:4',
            'ecoscore' => 'string|size:1',
            'finished_at' => 'date_format:d/m/Y H:i:s',
            'added_to_purchase_list_at' => 'date_format:d/m/Y H:i:s',
        ];
    }
}
