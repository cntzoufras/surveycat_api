<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class UpdateUserRequest extends BaseRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool {
        // You can add your authorization logic here
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'username'   => [
                'sometimes',
                'string',
                'max:255',
                'unique:users,username,' . $this->user->id, // Allows the current user to keep their username
            ],
            'email'      => [
                'sometimes',
                'email',
                'max:255',
                'unique:users,email,' . $this->user->id, // Allows the current user to keep their email
            ],
            'role'       => [
                'sometimes',
                'string',
                'in:registered,admin,superadmin', // Example roles, adjust as needed
            ],
            'first_name' => [
                'nullable',
                'string',
                'max:255',
            ],
            'last_name'  => [
                'nullable',
                'string',
                'max:255',
            ],
            'avatar'     => [
                'nullable',
                'string', // Assuming avatar is stored as a URL or path, adjust if it's an upload
            ],
            'password'   => [
                'nullable',
                'string',
                'min:8', // Minimum length requirement, adjust as needed
                'confirmed', // Ensures password confirmation
            ],
        ];
    }

    /**
     * Customize the error messages for validation rules.
     *
     * @return array
     */
    public function messages() {
        return [
            'username.unique'    => 'The username has already been taken.',
            'email.unique'       => 'The email address has already been taken.',
            'role.in'            => 'The selected role is invalid.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }
}