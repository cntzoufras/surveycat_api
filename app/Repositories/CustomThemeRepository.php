<?php

namespace App\Repositories;

use App\Models\Theme\Theme;
use App\Models\Survey;
use Illuminate\Support\Collection;

class CustomThemeRepository
{
    /**
     * Create a new custom theme
     *
     * @param array $data
     * @return Theme
     */
    public function create(array $data): Theme
    {
        return Theme::create($data);
    }

    /**
     * Update an existing custom theme
     *
     * @param string $id
     * @param array $data
     * @return Theme
     */
    public function update(string $id, array $data): Theme
    {
        $theme = Theme::findOrFail($id);
        $theme->update($data);
        return $theme;
    }

    /**
     * Find a custom theme by ID
     *
     * @param string $id
     * @return Theme|null
     */
    public function find(string $id): ?Theme
    {
        return Theme::find($id);
    }

    /**
     * Find custom theme by survey ID
     *
     * @param string $surveyId
     * @return Theme|null
     */
    public function findBySurvey(string $surveyId): ?Theme
    {
        return Theme::with(['theme_setting', 'theme_setting.variable_palettes'])
                   ->where('survey_id', $surveyId)
                   ->where('is_custom', true)
                   ->first();
    }

    /**
     * Delete a custom theme
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        $theme = Theme::find($id);
        if ($theme && $theme->is_custom) {
            return $theme->delete();
        }
        return false;
    }

    /**
     * Get all custom themes
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return Theme::where('is_custom', true)->get();
    }

    /**
     * Prepare custom theme data by merging base theme settings with customizations
     *
     * @param Theme $baseTheme
     * @param array $customizations
     * @param string|null $baseThemeId
     * @param string|null $surveyId
     * @return array
     */
    public function prepareCustomThemeData(Theme $baseTheme, array $customizations, ?string $baseThemeId = null, ?string $surveyId = null): array
    {
        $baseSettings = $baseTheme->theme_setting ? $baseTheme->theme_setting->settings : [];
        // Prepare theme data
        $data = [
            'title' => $customizations['title'] ?? 'Custom Theme',
            'description' => $customizations['description'] ?? 'A custom theme',
            'is_custom' => true,
            'base_theme_id' => $baseThemeId ?? $baseTheme->id,
            'survey_id' => $surveyId,
        ];

        return $data;
    }

    /**
     * Update survey theme relationship
     *
     * @param Survey $survey
     * @param string $themeId
     * @return Survey
     */
    public function updateSurveyTheme(Survey $survey, string $themeId): Survey
    {
        $survey->theme_id = $themeId;
        $survey->save();
        return $survey;
    }

    /**
     * Reset survey to base theme
     *
     * @param Survey $survey
     * @param string $baseThemeId
     * @return Survey
     */
    public function resetToBaseTheme(Survey $survey, string $baseThemeId): Survey
    {
        $survey->theme_id = $baseThemeId;
        $survey->custom_theme_settings = null;
        $survey->save();
        return $survey;
    }
}
