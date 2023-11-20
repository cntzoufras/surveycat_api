<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurveySettings\StoreSurveySettingsRequest;
use App\Http\Requests\SurveySettings\UpdateSurveySettingsRequest;
use App\Models\Survey\SurveySetting;

class SurveySettingsController extends Controller {

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
    public function store(StoreSurveySettingsRequest $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SurveySetting $surveySettings) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SurveySetting $surveySettings) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSurveySettingsRequest $request, SurveySetting $surveySettings) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SurveySetting $surveySettings) {
        //
    }
}
