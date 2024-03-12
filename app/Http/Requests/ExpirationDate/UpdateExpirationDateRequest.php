<?php

namespace App\Http\Requests\ExpirationDate;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExpirationDateRequest extends FormRequest
{
    public static function dateRules(): string
    {
        return 'required|date_format:d/m/Y';
    }

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'date' => self::dateRules(),
        ];
    }
}
