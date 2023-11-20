<?php

namespace App\Repositories\Theme;

use App\Models\Theme\ThemeVariable;
use Illuminate\Support\Facades\DB;

class ThemeVariableRepository {

    public function index(array $params) {
        try {
            $limit = $params['limit'] ?? 10;
            return DB::transaction(function () use ($limit) {
                return ThemeVariable::query()->paginate($limit);
            });
        } catch (\Exception $e) {
            throw new \Exception($e, 500);
        }
    }

    public function resolveModel($theme_variable) {
        if ($theme_variable instanceof ThemeVariable) {
            return $theme_variable;
        }
        return ThemeVariable::query()->findOrFail($theme_variable);
    }

    public function getIfExist($theme_variable) {
        return ThemeVariable::query()->find($theme_variable);
    }

    public function update(ThemeVariable $theme_variable, array $params) {
        return DB::transaction(function () use ($params, $theme_variable) {
            $theme_variable->fill($params);
            $theme_variable->save();
            return $theme_variable;
        });
    }

    public function store(array $params): ThemeVariable {
        return DB::transaction(function () use ($params) {
            $theme_variable = new ThemeVariable();
            $theme_variable->fill($params);
            $theme_variable->save();
            return $theme_variable;
        });
    }

    public function delete(ThemeVariable $theme_variable) {
        return DB::transaction(function () use ($theme_variable) {
            $theme_variable->delete();
            return $theme_variable;
        });
    }

}