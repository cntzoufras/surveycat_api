<?php

namespace App\Http\Requests\ThemeSetting;

use App\Http\Requests\BaseRequest;

class StoreThemeSettingRequest extends BaseRequest {

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
            'settings'                            => 'sometimes|array',
            'settings.typography'                 => 'sometimes|array',
            'settings.typography.fontFamily'      => 'sometimes|string|max:255',
            'settings.typography.fontSize'        => 'sometimes|string|regex:/^\d+(px|em|rem|%)$/',
            'settings.typography.headingStyle'    => 'sometimes|array',
            'settings.typography.headingStyle.H1' => 'sometimes|string|max:255',
            'settings.typography.headingStyle.H2' => 'sometimes|string|max:255',
            'theme_id'                            => 'required|exists:themes,id',
            'settings.primary_background_alpha'   => 'sometimes|nullable|integer|gte:0|lte:100',
            'settings.layout'                     => 'sometimes|nullable|in:left,center,right',
            'settings.thumb'                      => 'sometime|nullable|string|regex:/^([a-zA-Z]:)?(\\[a-zA-Z0-9_-]+)+\\?$/',
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array {
        return [
            'settings.array'                            => 'The settings field must be a valid JSON structure.',
            'settings.typography.fontSize.regex'        => 'The font size must be a valid size in px, em, rem, or %.',
            'theme_id.required'                         => 'The theme ID is required.',
            'theme_id.exists'                           => 'The selected theme ID does not exist.',
            'settings.primary_background_alpha.integer' => 'The alpha value must be an integer between 0 and 100.',
            'settings.layout.in'                        => 'The layout must be either left, center, or right.',
            'settings.thumb.regex'                      => 'The thumbnail path must be a valid file path.',
        ];
    }
}
