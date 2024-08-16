<?php

namespace App\Exceptions;

class SurveyNotEditableException extends \Exception {

    /**
     * SurveyNotEditableException constructor.
     *
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message = "Survey is published and cannot be edited unless reverted to draft.", $code = 403) {
        parent::__construct($message, $code);
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render(\Illuminate\Http\Request $request): \Illuminate\Http\JsonResponse {
        return response()->json([
            'message' => $this->getMessage(),
        ], $this->getCode());
    }

}