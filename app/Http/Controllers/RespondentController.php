<?php

namespace App\Http\Controllers;

use App\Http\Requests\Respondent\StoreRespondentRequest;
use App\Services\RespondentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RespondentController extends Controller {

    protected RespondentService $respondent_service;

    public function __construct(RespondentService $respondent_service) {
        $this->respondent_service = $respondent_service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        $validated = $request->validate(['limit' => 'integer|sometimes|min:0|max:100']);
        return $this->respondent_service->index($validated);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): \App\Models\Respondent {
        return $this->respondent_service->store();
    }

    /**
     * Display the specified resource.
     */
    public function show($id): mixed {
        $validated = Validator::make(['id' => $id], [
            'id' => 'required|integer|gt:0|exists:respondents,id',
        ]);
        if ($validated->fails()) {
            return response()->json(['error' => 'Respondent does not exist'], 400);
        }
        return $this->respondent_service->show($validated);
    }

}
