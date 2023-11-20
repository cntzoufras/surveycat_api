<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurveySubmission\StoreSurveySubmissionsRequest;
use App\Http\Resources\SurveySubmissionsResource;
use App\Models\Survey\SurveySubmission;
use App\Services\SurveySubmissionService;
use Illuminate\Http\Request;

class SurveySubmissionController extends Controller {

    protected SurveySubmissionService $survey_submissions_service;

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
     * Remove the specified resource from storage.
     */
    public function destroy(SurveySubmission $SurveySubmissions) {
        //
    }
}
