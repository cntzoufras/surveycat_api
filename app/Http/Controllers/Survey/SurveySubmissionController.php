<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurveySubmission\StoreSurveySubmissionsRequest;
use App\Http\Requests\UpdateSurveySubmissionsRequest;
use App\Http\Resources\SurveySubmissionsResource;
use App\Models\Survey\SurveySubmission;
use App\Services\SurveySubmissionService;
use Illuminate\Http\Request;

class SurveySubmissionController extends Controller {

    protected $survey_submissions_service;

    public function __construct(SurveySubmissionService $survey_submissions_service) {
        $this->survey_submissions_service = $survey_submissions_service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        return SurveySubmissionsResource::collection($this->survey_submissions_service->index($request->all()));
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
    public function store(StoreSurveySubmissionsRequest $request) {
        return SurveySubmissionsResource::collection($this->survey_submissions_service->store($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show($id) {
        return $this->survey_submissions_service->show($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SurveySubmission $SurveySubmissions) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SurveySubmission $SurveySubmissions, UpdateSurveySubmissionsRequest $request,) {
        $validated = $request->validate(['pages' => 'string']);
        return SurveySubmissionsResource::make($this->survey_submissions_service->update($SurveySubmissions, $request->validated()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SurveySubmission $SurveySubmissions) {
        //
    }
}
