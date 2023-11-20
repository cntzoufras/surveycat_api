<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Http\Requests\Survey\StoreSurveyRequest;
use App\Http\Requests\Survey\UpdateSurveyRequest;
use App\Models\Survey\Survey;

class SurveyController extends Controller {

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
    public function store(StoreSurveyRequest $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Survey $survey) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Survey $survey) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSurveyRequest $request, Survey $survey) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Survey $survey) {
        //
    }
}
