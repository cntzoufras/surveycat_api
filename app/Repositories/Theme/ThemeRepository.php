<?php

namespace App\Repositories\Theme;

use App\Models\Theme\Theme;
use Illuminate\Support\Facades\DB;

class ThemeRepository {


    public function index(array $params) {
        try {
            $limit = $params['limit'] ?? 20;
            return DB::transaction(function () use ($limit) {
                return Theme::query()->paginate($limit);
            });
        } catch (\Exception $e) {
            throw new \Exception($e, 500);
        }
    }

    public function resolveModel($theme) {
        if ($theme instanceof Theme) {
            return $theme;
        }
        return Theme::query()->findOrFail($theme);
    }

    public function getIfExist($theme): mixed {
        return Theme::query()->find($theme);
    }

    public function update(Theme $theme, array $params) {
        return DB::transaction(function () use ($params, $theme) {
            $theme->fill($params);
            $theme->save();
            return $theme;
        });
    }

    public function store(array $params): Theme {
        return DB::transaction(function () use ($params) {
            $theme = new Theme();
            $theme->fill($params);
            $theme->save();
            return $theme;
        });
    }

    public function delete(Theme $theme) {
        return DB::transaction(function () use ($theme) {
            $theme->delete();
            return $theme;
        });
    }

}