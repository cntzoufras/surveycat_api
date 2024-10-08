<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler {

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register(): void {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Handle SurveyNotEditableException
        $this->renderable(function (SurveyNotEditableException $e, $request) {
            return response()->json(['message' => $e->getMessage()], 403);
        });

        // Handle general exceptions
//        $this->renderable(function (Throwable $e, $request) {
//            return response()->json(['message' => 'An unexpected error occurred.'], 500);
//        });
    }
}
