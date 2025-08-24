<?php

namespace App\Http\Controllers;

use App\Models\Survey\Survey;
use App\Services\CustomThemeService;
use App\Repositories\CustomThemeRepository;
use App\Models\Theme\Theme;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CustomThemeController extends Controller
{
    protected $customThemeService;
    protected $customThemeRepository;

    public function __construct(
        CustomThemeService $customThemeService,

    )
    {
        $this->customThemeService = $customThemeService;

    }

    /**
     * Create a new custom theme
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
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
    public function update(Request $request, string $themeId): JsonResponse
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
    public function resetToBaseTheme(string $surveyId): JsonResponse
    {
        try {
            $survey = Survey::findOrFail($surveyId);
            $baseTheme = $survey->theme && $survey->theme->base_theme_id
                ? Theme::query()->find($survey->theme->base_theme_id)
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
