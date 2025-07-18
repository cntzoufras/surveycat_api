<?php

namespace App\Http\Requests\SurveyPage;

use App\Http\Requests\BaseRequest;

class StoreSurveyPageRequest extends BaseRequest
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
            'title' => 'nullable|string|min:0|max:255',
            'description' => 'nullable|string|min:0|max:1000',
            'survey_id' => 'required|uuid|exists:surveys,id',
            'sort_index' => 'nullable|integer',
            'require_questions' => 'boolean|nullable',
        ];
    }
}
