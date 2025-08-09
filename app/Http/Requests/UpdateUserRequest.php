<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// It should extend FormRequest directly
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     * The route middleware handles authentication, so this is safe.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // Using 'sometimes' so validation only runs if the field is present
            'first_name' => [
                'sometimes', // Keep this to allow partial updates
                'nullable',  // Allows the user to clear their first name
                'string',
                'max:255',
            ],
            'last_name' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
            ],
            'email' => [
                'sometimes',
                'required',
                'email:rfc,dns', // This rule is still great for checking format and domain existence.
                'regex:/.+@.+\..+/',
                Rule::unique('users')->ignore($this->user()->id), 'max:255',
            ],
            'password' => [
                'sometimes',
                'required',
                'string',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised(),
            ],
        ];
    }

    /**
     * Customize the error messages for validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'The email address has already been taken.',
            'email.regex' => 'Please enter a valid email address.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }
}
