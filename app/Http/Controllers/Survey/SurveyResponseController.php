<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurveyResponse\StoreSurveyResponseRequest;
use App\Http\Requests\SurveyResponse\UpdateSurveyResponseRequest;
use App\Models\Response;

class SurveyResponseController extends Controller {

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
    public function store(StoreSurveyResponseRequest $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Response $surveyResponse) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Response $surveyResponse) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSurveyResponseRequest $request, Response $surveyResponse) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Response $surveyResponse) {
        //
    }
}
