<?php

namespace App\Http\Requests\Survey;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSurveyRequest extends FormRequest
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
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:1000',
            'survey_status_id' => 'sometimes|integer|in:1,2,3,4',
            'theme_id' => 'sometimes|uuid|exists:themes,id',
            'layout' => 'sometimes|string|in:single,multiple',
            'priority' => 'sometimes|in:low,medium,high',
            'user_id' => 'sometimes|uuid|exists:users,id',
        ];
    }
}
