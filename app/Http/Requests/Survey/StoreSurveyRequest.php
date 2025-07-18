<?php

namespace App\Http\Requests\Survey;

use App\Http\Requests\BaseRequest;

class StoreSurveyRequest extends BaseRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|nullable|max:255',
            'description' => 'string|nullable|max:1000',
            'survey_category_id' => 'nullable|integer|exists:survey_categories,id',
            'survey_status_id' => 'nullable|integer|exists:survey_statuses,id',
            'user_id' => 'required|uuid|exists:users,id',
            'theme_id' => 'nullable |uuid|exists:themes,id',
            'priority' => 'nullable|in:low,medium,high',
            'layout' => 'sometimes|in:single,multiple',
        ];
    }
}
