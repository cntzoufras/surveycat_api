<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurveyPage\StoreSurveyPageRequest;
use App\Http\Requests\SurveyPage\UpdateSurveyPageRequest;
use App\Models\Survey\SurveyPage;
use App\Services\Survey\SurveyQuestionService;
use Illuminate\Http\Request;

class SurveyPageController extends Controller {

    protected SurveyQuestionService $survey_question_service;

    public function __construct(SurveyQuestionService $survey_question_service) {
        $this->survey_question_service = $survey_question_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @throws \Exception
     */
    public function index(Request $request) {
        $validated = $request->validate(['limit' => 'integer|sometimes|']);
        return $this->survey_question_service->index($validated);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSurveyPageRequest $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SurveyPage $surveyPage) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SurveyPage $surveyPage) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSurveyPageRequest $request, SurveyPage $surveyPage) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SurveyPage $surveyPage) {
        //
    }
}
