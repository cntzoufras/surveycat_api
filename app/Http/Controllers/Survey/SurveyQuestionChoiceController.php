<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurveyQuestionChoice\StoreSurveyQuestionChoiceRequest;
use App\Http\Requests\SurveyQuestionChoice\UpdateSurveyQuestionChoiceRequest;
use App\Models\Survey\SurveyQuestionChoice;

class SurveyQuestionChoiceController extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
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
    public function store(StoreSurveyQuestionChoiceRequest $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SurveyQuestionChoice $surveyQuestionChoice) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SurveyQuestionChoice $surveyQuestionChoice) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSurveyQuestionChoiceRequest $request, SurveyQuestionChoice $surveyQuestionChoice) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SurveyQuestionChoice $surveyQuestionChoice) {
        //
    }
}
