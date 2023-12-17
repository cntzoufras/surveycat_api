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
            'title'                           => 'required|string|max:255',
            'is_required'                     => 'required|boolean',
            'question_type_id'                => 'required|integer|exists:question_types,id',
            'survey_page_id'                  => 'required|uuid|exists:survey_pages,id',
            'additional_settings'             => 'sometimes|array|allowed_keys:color,align,font_style,font_size,font_family',
            'additional_settings.color'       => ['required', 'regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/'],
            'additional_settings.align'       => 'in:left,center,right',
            'additional_settings.font_style'  => 'in:bold,italic,underline',
            'additional_settings.font_family' => 'in:Arial,Calibri,Verdana',
            'additional_settings.font_size'   => 'integer|gte:4|lte:40',
        ];
    }
}
