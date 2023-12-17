<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class FieldsValidationServiceProvider extends ServiceProvider {

    public function boot(): void {
        Validator::extend('allowed_keys', function ($attribute, $value, $parameters, $validator) {
            $allowedKeys = $parameters;
            foreach ($value as $key => $_) {
                if (!in_array($key, $allowedKeys)) {
                    return false;
                }
            }
            return true;
        });

        Validator::replacer('allowed_keys', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':keys', implode(', ', $parameters), "The :attribute may only contain keys: :keys.");
        });
    }
}
