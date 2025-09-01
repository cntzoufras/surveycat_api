<?php

namespace App\Repositories;

use App\Models\Respondent;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

class RespondentRepository
{

    public function index(array $params): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        try {
            $perPage = $params['per_page'] ?? ($params['limit'] ?? 10);
            $page = $params['page'] ?? null;
            $search = isset($params['search']) ? trim((string)$params['search']) : '';

            /** @var \App\Models\User|null $user */
            $user = Auth::user();
            $ownerId = Auth::id();
            $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();

            return DB::transaction(function () use ($perPage, $page, $search, $ownerId, $isAdmin) {
                $query = Respondent::query()
                    ->with('survey_response.survey')
                    // Scope to respondents tied to surveys owned by the authenticated user (unless admin)
                    ->when(!$isAdmin, function ($q) use ($ownerId) {
                        $q->whereExists(function ($sub) use ($ownerId) {
                            $sub->selectRaw('1')
                                ->from('survey_responses')
                                ->join('surveys', 'survey_responses.survey_id', '=', 'surveys.id')
                                ->whereColumn('survey_responses.respondent_id', 'respondents.id')
                                ->where('surveys.user_id', $ownerId);
                        });
                    });

                if ($search !== '') {
                    $driver = DB::getDriverName();
                    $likeOperator = $driver === 'pgsql' ? 'ilike' : 'like';
                    // For numeric-only searches, enforce strict age equality to avoid partial matches on other fields
                    if (is_numeric($search)) {
                        $query->where('age', '=', (int) $search);
                    } else {
                        // Text search limited to email, age (as text), survey title, and UUID id
                        $query->where(function ($q) use ($search, $likeOperator) {
                            // Email partial match
                            $q->where('email', $likeOperator, "%$search%")
                              // Survey title via relationship
                              ->orWhereHas('survey_response.survey', function ($sq) use ($search, $likeOperator) {
                                  $sq->where('title', $likeOperator, "%$search%");
                              })
                              // Age partial text match (non-numeric search terms only)
                              ->orWhere('age', $likeOperator, "%$search%");

                            // Only compare by UUID when the search term is a valid UUID to avoid PG uuid cast errors
                            if (Uuid::isValid($search)) {
                                $q->orWhere('id', '=', $search);
                            }
                        });
                    }
                }

                if ($page !== null) {
                    return $query->paginate($perPage, ['*'], 'page', (int)$page);
                }

                return $query->paginate($perPage);
            });
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    public function store()
    {
        return DB::transaction(function () {
            $survey_respondent = new Respondent();
            $survey_respondent->save();
            return $survey_respondent->id;
        });
    }

    public function update(Respondent $respondent, array $params): \App\Models\Respondent
    {
        if (!$respondent->exists) {
            $respondent = Respondent::query()
                ->findOrFail($respondent->id);
        }

        return DB::transaction(function () use ($respondent, $params) {
            $respondent->fill($params);
            $respondent->save();
            return $respondent;
        });
    }

}
