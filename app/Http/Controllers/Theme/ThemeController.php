<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use App\Http\Requests\Theme\StoreThemeRequest;
use App\Http\Requests\Theme\UpdateThemeRequest;
use App\Models\Theme\Theme;
use App\Services\Theme\ThemeService;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ThemeController extends Controller {

    protected ThemeService $theme_service;

    public function __construct(ThemeService $theme_service) {
        $this->theme_service = $theme_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @throws \Exception
     */
    public function index(Request $request): LengthAwarePaginator {
        $validated = $request->validate(['limit' => 'integer|sometimes|min:0|max:100']);
        return $this->theme_service->index($validated);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreThemeRequest $request): Theme {
        return $this->theme_service->store($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @throws \Exception
     */
    public function show(Theme $theme): Theme
    {
        try {
            $theme->load('theme_setting.variable_palettes');
            return $theme;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateThemeRequest $request, Theme $theme): Theme {
        return $this->theme_service->update($theme, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Theme $theme): Theme {
        return $this->theme_service->delete($theme);
    }
}
