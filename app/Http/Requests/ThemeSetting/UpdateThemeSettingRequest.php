<?php

namespace App\Http\Requests\ThemeSetting;

use App\Http\Requests\BaseRequest;

class UpdateThemeSettingRequest extends BaseRequest {

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
            'settings'                             => [
                'required',
                'array',
            ],
            'settings.colorScheme'                 => 'required|array',
            'settings.colorScheme.primaryColor'    => 'required|string|regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/',
            'settings.colorScheme.secondaryColor'  => 'required|string|regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/',
            'settings.colorScheme.backgroundColor' => 'required|string|regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/',
            'settings.colorScheme.textColor'       => 'required|string|regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/',
            'settings.colorScheme.headerColor'     => 'required|string|regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/',
            'settings.typography'                  => 'required|array',
            'settings.typography.fontFamily'       => 'required|string|max:255',
            'settings.typography.fontSize'         => 'required|string|regex:/^\d+(px|em|rem|%)$/',
            'settings.typography.headingStyle'     => 'required|array',
            'settings.typography.headingStyle.H1'  => 'required|string|max:255',
            'settings.typography.headingStyle.H2'  => 'required|string|max:255',
            'theme_id'                             => 'required|exists:themes,id',
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array {
        return [
            'settings.required' => 'The settings field is required.',
            'settings.array'    => 'The settings field must be a valid JSON structure.',
            'theme_id.required' => 'The theme ID is required.',
            'theme_id.exists'   => 'The selected theme ID does not exist.',
        ];
    }
}
