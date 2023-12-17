<?php

namespace App\Http\Requests\SurveyResponse;

use App\Http\Requests\BaseRequest;

class StoreSurveyResponseRequest extends BaseRequest {

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array {
        return [
            'started_at' => 'required|date',
            'survey_id'  => 'uuid|exists:surveys,id',
            'country'    => 'required|exists:countries,id',
            'timezone'   => 'required|string',
        ];
    }
}
