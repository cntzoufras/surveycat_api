<?php

namespace App\Http\Requests\SurveyResponse;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSurveyResponseRequest extends FormRequest
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
            'completed_at' => 'sometimes|date',
        ];
    }
}
