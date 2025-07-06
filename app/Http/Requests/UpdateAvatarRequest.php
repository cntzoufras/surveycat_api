<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAvatarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // The route middleware already protects this
    }

    public function rules(): array
    {
        return [
            // Validate only the 'avatar' field
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'], // 2MB Max
        ];
    }
}
