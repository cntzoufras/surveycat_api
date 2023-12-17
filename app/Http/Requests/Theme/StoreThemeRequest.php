<?php

namespace App\Http\Requests\Theme;

use App\Http\Requests\BaseRequest;

class StoreThemeRequest extends BaseRequest {

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
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id'     => 'nullable|uuid|exists:users,id',
        ];
    }
}
