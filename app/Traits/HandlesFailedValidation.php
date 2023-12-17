<?php

namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait HandlesFailedValidation {

    protected function failedValidation(Validator $validator) {
        $response = response()->json([
            'message' => 'Validation errors',
            'errors'  => $validator->errors(),
        ], 422);

        throw new HttpResponseException($response);
    }
}
