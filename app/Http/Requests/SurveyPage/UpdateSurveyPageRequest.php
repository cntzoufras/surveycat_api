<?php

namespace App\Http\Requests\SurveyPage;

use App\Http\Requests\BaseRequest;

class UpdateSurveyPageRequest extends BaseRequest {

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array {
        return [
            'title'             => 'sometimes|string|max:255',
            'description'       => 'sometimes|string|max:255',
            'layout'            => 'sometimes|string|in:single,multiple',
            'sort_index'        => 'sometimes|integer',
            'require_questions' => 'sometimes|boolean',
            'survey_id'         => 'uuid|in:surveys,id',
        ];
    }
}
    