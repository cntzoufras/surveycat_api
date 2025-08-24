<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use App\Http\Requests\ThemeSetting\StoreThemeSettingRequest;
use App\Http\Requests\ThemeSetting\UpdateThemeSettingRequest;
use App\Models\Theme\ThemeSetting;
use Illuminate\Http\JsonResponse;

class ThemeSettingsController extends Controller {

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreThemeSettingRequest $request): JsonResponse {
        $themeSetting = ThemeSetting::create($request->validated());
        return response()->json($themeSetting, 201);
    }

}
