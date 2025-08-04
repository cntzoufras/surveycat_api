<?php

namespace App\Services;

use App\Repositories\CustomThemeRepository;
use App\Models\Theme\Theme;
use App\Models\Survey;
use Exception;

class CustomThemeService
{
    protected $customThemeRepository;

    public function __construct(CustomThemeRepository $customThemeRepository)
    {
        $this->customThemeRepository = $customThemeRepository;
    }

    /**
     * Create a new custom theme based on a base theme
     *
     * @param Theme $baseTheme
     * @param array $customizations
     * @param string $surveyId
     * @return Theme
     * @throws Exception
     */
    public function createCustomTheme(Theme $baseTheme, array $customizations, string $surveyId): Theme
    {
        try {
            // Validate base theme is not already custom
            if ($baseTheme->is_custom) {
                throw new Exception('Cannot create custom theme from another custom theme');
            }

            // Ensure base theme has theme_setting
            if (!$baseTheme->theme_setting) {
                // Create default theme setting for base theme
                $baseTheme->theme_setting()->create([
                    'settings' => [
                        'colors' => [
                            'primary' => '#2196F3',
                            'secondary' => '#757575',
                            'background' => '#FFFFFF',
                            'text' => '#212121',
                        ],
                        'typography' => [
                            'fontFamily' => 'Arial, sans-serif',
                            'fontSize' => '16px',
                        ],
                        'layout' => [
                            'maxWidth' => '800px',
                        ],
                    ],
                ]);
                $baseTheme->load('theme_setting');
            }

            // Prepare custom theme data
            $customThemeData = $this->customThemeRepository->prepareCustomThemeData($baseTheme, $customizations, $baseTheme->id, $surveyId);

            // Create the custom theme
            $customTheme = $this->customThemeRepository->create($customThemeData);
            
            // Create theme setting for custom theme
            $customTheme->theme_setting()->create([
                'settings' => array_merge(
                    $baseTheme->theme_setting->settings ?? [],
                    $customizations
                ),
            ]);

            return $customTheme->load('theme_setting', 'theme_setting.variable_palettes');
        } catch (Exception $e) {
            throw new Exception('Failed to create custom theme: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing custom theme
     *
     * @param string $themeId
     * @param array $customizations
     * @return Theme
     * @throws Exception
     */
    public function updateCustomTheme(string $themeId, array $customizations): Theme
    {
        try {
            $theme = $this->customThemeRepository->find($themeId);
            
            if (!$theme) {
                throw new Exception('Custom theme not found');
            }

            if (!$theme->is_custom) {
                throw new Exception('Cannot update non-custom theme');
            }

            // Ensure theme has theme_setting
            if (!$theme->theme_setting) {
                $theme->theme_setting()->create([
                    'settings' => $customizations,
                ]);
            } else {
                // Merge existing settings with new customizations
                $existingSettings = $theme->theme_setting->settings ?? [];
                $updatedSettings = array_merge($existingSettings, $customizations);
                
                $theme->theme_setting->update([
                    'settings' => $updatedSettings,
                ]);
            }

            return $theme->fresh(['theme_setting', 'theme_setting.variable_palettes']);
        } catch (Exception $e) {
            throw new Exception('Failed to update custom theme: ' . $e->getMessage());
        }
    }

    /**
     * Get custom theme for a survey
     *
     * @param string $surveyId
     * @return Theme|null
     */
    public function getCustomThemeForSurvey(string $surveyId): ?Theme
    {
        return $this->customThemeRepository->findBySurvey($surveyId);
    }

    /**
     * Delete a custom theme
     *
     * @param string $themeId
     * @return bool
     * @throws Exception
     */
    public function deleteCustomTheme(string $themeId): bool
    {
        try {
            return $this->customThemeRepository->delete($themeId);
        } catch (Exception $e) {
            throw new Exception('Failed to delete custom theme: ' . $e->getMessage());
        }
    }

    /**
     * Assign theme to survey
     *
     * @param Survey $survey
     * @param string $themeId
     * @return Survey
     * @throws Exception
     */
    public function assignThemeToSurvey(Survey $survey, string $themeId): Survey
    {
        try {
            return $this->customThemeRepository->updateSurveyTheme($survey, $themeId);
        } catch (Exception $e) {
            throw new Exception('Failed to assign theme to survey: ' . $e->getMessage());
        }
    }

    /**
     * Reset survey to base theme
     *
     * @param Survey $survey
     * @param string $baseThemeId
     * @return Survey
     * @throws Exception
     */
    public function resetToBaseTheme(Survey $survey, string $baseThemeId): Survey
    {
        try {
            return $this->customThemeRepository->resetToBaseTheme($survey, $baseThemeId);
        } catch (Exception $e) {
            throw new Exception('Failed to reset to base theme: ' . $e->getMessage());
        }
    }

    /**
     * Validate theme data
     *
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function validateThemeData(array $data): bool
    {
        if (empty($data['base_theme_id'])) {
            throw new Exception('Base theme ID is required');
        }

        if (empty($data['customizations'])) {
            throw new Exception('Customizations are required');
        }

        return true;
    }

    /**
     * Get base theme for custom theme
     *
     * @param string $baseThemeId
     * @return Theme
     * @throws Exception
     */
    public function getBaseTheme(string $baseThemeId): Theme
    {
        $baseTheme = $this->customThemeRepository->find($baseThemeId);
        
        if (!$baseTheme) {
            throw new Exception('Base theme not found');
        }

        if ($baseTheme->is_custom) {
            throw new Exception('Cannot use custom theme as base');
        }

        return $baseTheme;
    }
}
