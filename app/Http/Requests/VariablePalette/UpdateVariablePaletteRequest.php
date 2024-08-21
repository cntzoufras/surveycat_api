<?php

namespace App\Http\Requests\VariablePalette;

use App\Http\Requests\BaseRequest;

class UpdateVariablePaletteRequest extends BaseRequest {

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        // Change this to true if you want to authorize the request
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'title_color'          => 'required|string|regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/',
            'question_color'       => 'required|string|regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/',
            'answer_color'         => 'required|string|regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/',
            'primary_accent'       => 'required|string|regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/',
            'primary_background'   => 'required|string|regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/',
            'secondary_accent'     => 'required|string|regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/',
            'secondary_background' => 'required|string|regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/',
            'theme_setting_id'     => 'nullable|exists:theme_settings,id',
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array {
        return [
            'title_color.required'          => 'The title color is required.',
            'title_color.regex'             => 'The title color must be a valid hex color code.',
            'question_color.required'       => 'The question color is required.',
            'question_color.regex'          => 'The question color must be a valid hex color code.',
            'answer_color.required'         => 'The answer color is required.',
            'answer_color.regex'            => 'The answer color must be a valid hex color code.',
            'primary_accent.required'       => 'The primary accent color is required.',
            'primary_accent.regex'          => 'The primary accent color must be a valid hex color code.',
            'primary_background.required'   => 'The primary background color is required.',
            'primary_background.regex'      => 'The primary background color must be a valid hex color code.',
            'secondary_accent.required'     => 'The secondary accent color is required.',
            'secondary_accent.regex'        => 'The secondary accent color must be a valid hex color code.',
            'secondary_background.required' => 'The secondary background color is required.',
            'secondary_background.regex'    => 'The secondary background color must be a valid hex color code.',
            'theme_setting_id.exists'       => 'The selected theme setting ID does not exist.',
        ];
    }
}
