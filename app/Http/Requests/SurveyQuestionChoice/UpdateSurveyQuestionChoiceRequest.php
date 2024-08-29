<?php

namespace App\Http\Requests\SurveyQuestionChoice;

use App\Http\Requests\BaseRequest;

class UpdateSurveyQuestionChoiceRequest extends BaseRequest {

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'content'            => 'sometimes|string|max:255',
            'sort_index'         => 'sometimes|integer|min:0',
            'survey_question_id' => 'required|uuid|exists:survey_questions,id',
        ];
    }
}
