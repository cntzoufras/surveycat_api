<?php

namespace App\Http\Controllers;

use App\Http\Requests\GlobalSearchRequest;
use App\Services\GlobalSearchService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class GlobalSearchController extends Controller
{
    protected GlobalSearchService $global_search_service;

    public function __construct(GlobalSearchService $globalSearchService)
    {
        $this->global_search_service = $globalSearchService;
    }

    /**
     * Perform a global search across different models.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(GlobalSearchRequest $request): JsonResponse
    {
        $search_term = $request->validated()['query'];
        $results = $this->global_search_service->search($search_term);
        return response()->json($results);
    }

}
