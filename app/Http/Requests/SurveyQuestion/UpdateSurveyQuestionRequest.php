<?php

namespace App\Http\Requests\SurveyQuestion;

use App\Http\Requests\BaseRequest;
use App\Traits\HandlesFailedValidation;

class UpdateSurveyQuestionRequest extends BaseRequest {

    use HandlesFailedValidation;

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
            'title'                           => 'sometimes|string|max:255',
            'is_required'                     => 'sometimes|boolean',
            'question_type_id'                => 'sometimes|integer|exists:question_types,id',
            'survey_page_id'                  => 'sometimes|uuid|exists:survey_pages,id',
            'additional_settings'             => 'array|allowed_keys:color,align,font_style,font_size,font_family',
            'additional_settings.color'       => ['sometimes', 'regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/'],
            'additional_settings.align'       => 'sometimes|in:left,center,right',
            'additional_settings.font_style'  => 'sometimes|in:bold,italic,underline',
            'additional_settings.font_family' => 'sometimes|in:Arial,Calibri,Verdana',
            'additional_settings.font_size'   => 'sometimes|integer|gte:4|lte:40',
        ];
    }
}
