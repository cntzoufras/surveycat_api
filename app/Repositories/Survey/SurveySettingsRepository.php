<?php

namespace App\Repositories\Survey;

use App\Models\Survey\SurveySetting;
use Illuminate\Support\Facades\DB;

class SurveySettingsRepository {

    public function index(array $params) {

        try {
            $limit = $params['limit'] ?? 10;
            return DB::transaction(function () use ($limit) {
                return SurveySetting::query()->paginate($limit);
            });
        } catch (\Exception $e) {
            throw new \Exception($e, 500);
        }
    }

    public function resolveModel($survey_setting) {
        if ($survey_setting instanceof SurveySetting) {
            return $survey_setting;
        }
        return SurveySetting::query()->findOrFail($survey_setting);
    }

    public function getIfExist($survey_setting) {
        return SurveySetting::query()->find($survey_setting);
    }

    public function update(SurveySetting $survey_setting, array $params) {
        return DB::transaction(function () use ($params, $survey_setting) {
            $survey_setting->fill($params);
            $survey_setting->save();
            return $survey_setting;
        });
    }

    public function store(array $params): SurveySetting {
        return DB::transaction(function () use ($params) {
            $survey_setting = new SurveySetting();
            $survey_setting->fill($params);
            $survey_setting->save();
            return $survey_setting;
        });
    }

    public function delete(SurveySetting $survey_setting) {
        return DB::transaction(function () use ($survey_setting) {
            $survey_setting->delete();
            return $survey_setting;
        });
    }

}