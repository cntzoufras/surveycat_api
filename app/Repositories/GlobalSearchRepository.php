<?php

namespace App\Repositories;

use App\Models\Survey\Survey;
use App\Models\Survey\SurveyPage;
use Illuminate\Support\Facades\Auth;

class GlobalSearchRepository
{
    /**
     * Search for a term within the authenticated user's surveys.
     *
     * @param string $term
     * @return array
     */
    public function search(string $term): array
    {
        $searchTerm = '%' . $term . '%';
        $userId = Auth::id();

        // Return no survey for non logged in users
        if (!$userId) {
            return ['surveys' => []];
        }

        $firstPageIdSubquery = SurveyPage::select('id')
            ->whereColumn('survey_id', 'surveys.id')
            ->orderBy('created_at', 'asc')
            ->limit(1);

        $surveys = Survey::query()
            ->where('user_id', $userId) // only logged in user surveys
            ->where(function ($query) use ($searchTerm) {
                $query->where('title', 'LIKE', $searchTerm)
                    ->orWhere('description', 'LIKE', $searchTerm);
            })
            ->select('id', 'title', 'description')
            ->selectSub($firstPageIdSubquery, 'first_page_id')
            ->take(10) // limit response results
            ->get();

        return [
            'surveys' => $surveys,
        ];
    }
}
