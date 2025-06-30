<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Services\Survey\QuestionTypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

#[AllowDynamicProperties] class QuestionTypeController extends Controller
{

    protected QuestionTypeService $survey_question_service;

    public function __construct(QuestionTypeService $question_type__service)
    {
        $this->question_type_service = $question_type__service;
    }

    /**
     * Display a listing of the resource.
     *
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $validated = $request->validate(['limit' => 'integer|sometimes|min:0|max:100']);
        return $this->question_type_service->index($validated);
    }

}
