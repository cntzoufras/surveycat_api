<?php

namespace App\Http\Requests\SurveyCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class IndexSurveyCategoryRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * @param \Illuminate\Contracts\Validation\Validator $validator
     */
    protected function failedValidation(Validator $validator) {
        $response = response()->json([
            'message' => 'Validation errors',
            'errors'  => $validator->errors(),
        ], 422);

        throw new HttpResponseException($response);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'limit' => 'nullable|integer|min:1|max:50',
            'page'  => 'nullable|integer|min:1|max:50',
        ];
    }
}
