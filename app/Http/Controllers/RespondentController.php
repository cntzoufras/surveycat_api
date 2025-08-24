<?php

namespace App\Http\Controllers;

use App\Http\Requests\Respondent\StoreRespondentRequest;
use App\Http\Requests\Respondent\UpdateRespondentRequest;
use App\Models\Respondent;
use App\Services\RespondentService;
use Illuminate\Http\Request;

class RespondentController extends Controller
{

    protected RespondentService $respondent_service;

    public function __construct(RespondentService $respondent_service)
    {
        $this->respondent_service = $respondent_service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $validated = $request->validate([
            'limit' => 'integer|sometimes|min:0|max:100',
            'per_page' => 'integer|sometimes|min:1|max:100',
            'page' => 'integer|sometimes|min:1',
            'search' => 'string|sometimes|max:255',
        ]);

        return $this->respondent_service->index($validated);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): string
    {
        return $this->respondent_service->store();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRespondentRequest $request, Respondent $respondent): Respondent
    {
        return $this->respondent_service->update($respondent, $request->validated());
    }

}
