<?php

namespace App\Http\Requests\Respondent;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRespondentRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'age' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'gender' => ['sometimes', 'nullable', 'string', 'in:male,female,other'],
            'email' => ['sometimes', 'nullable', 'string', 'email'],
        ];
    }
}
