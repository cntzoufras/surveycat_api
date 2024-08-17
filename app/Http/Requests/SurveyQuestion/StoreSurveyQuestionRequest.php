<?php

namespace App\Http\Requests\SurveyQuestion;

use App\Http\Requests\BaseRequest;

class StoreSurveyQuestionRequest extends BaseRequest {

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
            'title'            => 'required|string|max:255',
            'is_required'      => 'required|boolean',
            'question_type_id' => 'required|integer|exists:question_types,id',
            'survey_page_id'   => 'required|uuid|exists:survey_pages,id',
        ];
    }
}
