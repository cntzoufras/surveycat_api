<?php

namespace App\Repositories\Survey;

use App\Models\Survey\Survey;
use App\Models\Survey\SurveySubmission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;

class SurveyRepository implements SurveyRepositoryInterface
{

    public function index(array $params)
    {
        try {
            $limit = $params['limit'] ?? 50;
            return DB::transaction(function () use ($limit) {
                return Survey::query()->paginate($limit);
            });
        } catch (\Exception $e) {
            throw new \Exception($e, 500);
        }
    }

    public function resolveModel($surveys)
    {
        if ($surveys instanceof Survey) {
            return $surveys;
        }
        return Survey::with('survey_status')->findOrFail($surveys);
    }

    public function getIfExist($survey)
    {
        return Survey::query()->find($survey);
    }

    public function update(Survey $survey, array $params)
    {
        return DB::transaction(function () use ($params, $survey) {
            $survey->fill($params);
            $survey->save();
            return $survey;
        });
    }

    public function store(array $params): Survey
    {
        return DB::transaction(function () use ($params) {
            $survey = new Survey();
            $survey->fill($params);
            $survey->save();
            return $survey;
        });
    }


    public function destroy(Survey $survey)
    {
        return DB::transaction(function () use ($survey) {
            $survey->delete();
            return $survey;
        });
    }

    /**
     * @throws \Exception
     */
    public function getStockSurveys()
    {
        try {
            return DB::transaction(function () {
                return Survey::query()->where('is_stock', '=', true)->paginate();
            });
        } catch (\Exception $e) {
            throw new \Exception($e, 500);
        }
    }

    public function getSurveysForUser(string $user_id): Collection
    {
        return Survey::with([
            'theme:id,title',
            'survey_pages',
            'survey_category:id,title'
        ])
            ->where('user_id', $user_id)
            ->get();
    }

    public function getSurveysWithThemesAndPages(): Collection
    {
        return Survey::with(['theme:id,title', 'survey_pages'])
            ->where('user_id', Auth::id())  // Filter by the logged-in user
            ->where('is_stock', false)       // Exclude stock surveys
            ->get();
    }

    public function getSurveyWithDetails($survey_id): Survey
    {
        return Survey::with([
            'theme:id,title',
            'survey_category:id,title',
            'survey_pages' => function ($query) {
                $query->orderBy('sort_index');
            },
        ])
            ->where('user_id', Auth::id())
            ->findOrFail($survey_id);
    }

    public function getSurveyPreview($survey_id): Survey
    {
        return Survey::with([
            'theme:id,title',
            'survey_category:id,title',
            'survey_pages' => function ($survey_page_query) {
                $survey_page_query->orderBy('sort_index')
                    ->with(['survey_questions' => function ($survey_question_query) {
                        $survey_question_query->orderBy('sort_index')
                            ->with(['survey_question_choices' => function ($survey_question_choice_query) {
                                $survey_question_choice_query->orderBy('sort_index');
                            }]);
                    }]);
            },
        ])
            ->where('user_id', Auth::id())
            ->findOrFail($survey_id);
    }

    public function getPublicSurveyBySlug(string $slug): Survey
    {
        return Survey::withoutGlobalScopes()->where('public_link', $slug)
            ->with([
                'theme:id,title,description,footer',
                'theme.theme_setting',
                'survey_category:id,title,description',
                'survey_pages' => function ($query) {
                    $query->orderBy('sort_index')
                        ->with(['survey_questions' => function ($query) {
                            $query->select([
                                'id', 'title', 'survey_page_id', 'is_required',
                                'question_type_id', 'additional_settings', 'question_tags',
                            ])
                                ->with('survey_question_choices');
                        },
                        ]);
                },
                'survey_settings',
            ])
            ->firstOrFail();
    }

    /**
     * Get the survey and submission counts for a user in a single query.
     *
     * @param string $user_id
     * @return \stdClass O(n) object with 'surveys_count' and 'submissions_count'
     */
    public function getProfileSurveyCountsForUser(string $user_id): \stdClass
    {
        // Subquery to count the user's surveys
        $surveysCount = Survey::selectRaw('count(*)')->where('user_id', $user_id);

        // Subquery to count submissions on that user's surveys
        $submissionsCount = SurveySubmission::selectRaw('count(*)')
            ->whereHas('survey', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            });

        // Build the final single query
        return DB::query()
            ->selectSub($surveysCount, 'surveys_count')
            ->selectSub($submissionsCount, 'submissions_count')
            ->first();
    }
}
