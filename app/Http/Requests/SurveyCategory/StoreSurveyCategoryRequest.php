<?php

namespace App\Http\Requests\SurveyCategory;

use App\Http\Requests\BaseRequest;

class StoreSurveyCategoryRequest extends BaseRequest {

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
            'title' => 'required|unique:survey_categories|max:255',
        ];
    }
}
