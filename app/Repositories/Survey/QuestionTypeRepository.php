<?php

namespace App\Repositories\Survey;

use App\Models\QuestionType;
use Illuminate\Support\Facades\DB;

class QuestionTypeRepository
{

    /**
     * @throws \Exception
     */
    public function index(array $params)
    {
        try {
            $limit = $params['limit'] ?? 20;
            return DB::transaction(function () use ($limit) {
                return QuestionType::query()->get();
            });
        } catch (\Exception $e) {
            throw new \Exception($e, 500);
        }
    }

}
