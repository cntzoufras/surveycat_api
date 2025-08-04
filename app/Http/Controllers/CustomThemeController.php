<?php

namespace App\Http\Controllers;

use App\Services\CustomThemeService;
use App\Repositories\CustomThemeRepository;
use App\Models\Theme\Theme;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CustomThemeController extends Controller
{
    protected $customThemeService;
    protected $customThemeRepository;

    public function __construct(
        CustomThemeService $customThemeService,
        CustomThemeRepository $customThemeRepository
    ) {
        $this->customThemeService = $customThemeService;
        $this->customThemeRepository = $customThemeRepository;
    }

    /**
     * Create a new custom theme
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'base_theme_id' => 'required|uuid|exists:themes,id',
            'customizations' => 'required|array',
            'survey_id' => 'required|uuid|exists:surveys,id',
        ]);

        try {
            $baseTheme = Theme::findOrFail($request->base_theme_id);
            $customTheme = $this->customThemeService->createCustomTheme(
                $baseTheme,
                $request->customizations,
                $request->survey_id
            );

            return response()->json([
                'message' => 'Custom theme created successfully',
                'data' => $customTheme
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create custom theme',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing custom theme
     *
     * @param Request $request
     * @param string $themeId
     * @return JsonResponse
     */
    public function update(Request $request, $themeId)
    {
        $request->validate([
            'customizations' => 'required|array',
        ]);

        try {
            $customTheme = $this->customThemeService->updateCustomTheme(
                $themeId,
                $request->customizations
            );

            return response()->json([
                'message' => 'Custom theme updated successfully',
                'data' => $customTheme
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update custom theme',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get custom theme for a survey
     */
    public function showBySurvey(Survey $survey): JsonResponse
    {
        $theme = $this->customThemeRepository->getCustomThemeForSurvey($survey);

        if (!$theme) {
            return response()->json(['error' => 'Custom theme not found'], 404);
        }

        return response()->json($theme->load('theme_setting', 'theme_setting.variable_palettes'));
    }

    /**
     * Delete a custom theme
     */
    public function destroy(Theme $theme): JsonResponse
    {
        try {
            $this->customThemeService->deleteCustomTheme($theme);

            return response()->json(['message' => 'Custom theme deleted successfully']);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete custom theme',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset survey to base theme
     *
     * @param string $surveyId
     * @return JsonResponse
     */
    public function resetToBaseTheme($surveyId)
    {
        try {
            $survey = Survey::findOrFail($surveyId);
            $baseTheme = $survey->theme && $survey->theme->base_theme_id 
                ? Theme::find($survey->theme->base_theme_id) 
                : null;

            if (!$baseTheme) {
                return response()->json([
                    'message' => 'Base theme not found'
                ], 404);
            }

            // Reset survey to base theme using service
            $survey = $this->customThemeService->resetToBaseTheme($survey, $baseTheme->id);

            return response()->json([
                'message' => 'Survey reset to base theme successfully',
                'data' => $survey
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to reset to base theme',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
