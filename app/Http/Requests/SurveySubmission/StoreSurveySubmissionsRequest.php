<?php

namespace App\Http\Requests\SurveySubmission;

use App\Http\Requests\BaseRequest;

class StoreSurveySubmissionsRequest extends BaseRequest
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
            'submission_data' => 'required|array',
            'survey_id' => 'required|uuid|exists:surveys,id',
            'survey_response_id' => 'required|uuid|exists:survey_responses,id',
        ];
    }
}
