<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Http\Requests\SurveySubmission\StoreSurveySubmissionsRequest;
use App\Models\Survey\SurveySubmission;
use App\Services\Survey\SurveySubmissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SurveySubmissionController extends Controller
{

    protected SurveySubmissionService $survey_submission_service;

    public function __construct(SurveySubmissionService $survey_submission_service)
    {
        $this->survey_submission_service = $survey_submission_service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'limit' => 'integer|sometimes|min:0|max:100',
            'per_page' => 'integer|sometimes|min:1|max:100',
            'page' => 'integer|sometimes|min:1',
            'search' => 'string|sometimes|max:255',
        ]);
        return $this->survey_submission_service->index($validated);
    }

    public function store(StoreSurveySubmissionsRequest $request): SurveySubmission
    {
        return $this->survey_submission_service->store($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @throws \Exception
     */
    public function show(Request $request): mixed
    {
        try {
            if (isset($request['id'])) {
                Validator::validate(['id' => $request['id']], [
                    'id' => 'uuid|required|exists:survey_submissions,id',
                ]);
                return $this->survey_submission_service->show($request['id']);
            }
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        }
        return null;
    }

    /**
     * Display the specified resource.
     *
     * @throws \Exception
     */
    public function getSurveySubmissionsCountForUser(): JsonResponse
    {

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $survey_submissions_count_user = $this->survey_submission_service->getSurveySubmissionsCountForUser($user->id);
        return response()->json($survey_submissions_count_user);
    }


}
