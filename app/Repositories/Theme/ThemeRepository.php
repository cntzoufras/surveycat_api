<?php

namespace App\Repositories\Theme;

use App\Models\Theme\Theme;
use Illuminate\Support\Facades\DB;

class ThemeRepository
{


    public function index(array $params)
    {
        try {
            $limit = $params['limit'] ?? 20;
            return DB::transaction(function () use ($limit) {
                return Theme::query()->paginate($limit);
            });
        } catch (\Exception $e) {
            throw new \Exception($e, 500);
        }
    }

    public function resolveModel($theme)
    {
        if ($theme instanceof Theme) {
            \Log::info('ThemeRepository@resolveModel: Theme instance provided', ['theme_id' => $theme->id]);
            return $theme;
        }
        
        \Log::info('ThemeRepository@resolveModel: Fetching theme by ID with relationships', ['theme_id' => $theme]);
        $themeModel = Theme::query()->with('theme_setting.variable_palettes')->findOrFail($theme);
        
        \Log::info('ThemeRepository@resolveModel: Theme fetched', [
            'theme_id' => $themeModel->id,
            'has_theme_setting' => $themeModel->relationLoaded('theme_setting'),
            'theme_setting' => $themeModel->theme_setting ? $themeModel->theme_setting->toArray() : null,
        ]);
        
        if ($themeModel->theme_setting) {
            \Log::info('ThemeRepository@resolveModel: Variable palettes fetched', [
                'theme_id' => $themeModel->id,
                'palettes_count' => $themeModel->theme_setting->variable_palettes->count(),
                'has_variable_palettes' => $themeModel->theme_setting->relationLoaded('variable_palettes'),
            ]);
        }
        
        return $themeModel;
    }

    public function getIfExist($theme): mixed
    {
        return Theme::query()->find($theme);
    }

    public function update(Theme $theme, array $params)
    {
        return DB::transaction(function () use ($params, $theme) {
            $theme->fill($params);
            $theme->save();
            return $theme;
        });
    }

    public function store(array $params): Theme
    {
        return DB::transaction(function () use ($params) {
            $theme = new Theme();
            $theme->fill($params);
            $theme->save();
            return $theme;
        });
    }

    public function delete(Theme $theme)
    {
        return DB::transaction(function () use ($theme) {
            $theme->delete();
            return $theme;
        });
    }

}
