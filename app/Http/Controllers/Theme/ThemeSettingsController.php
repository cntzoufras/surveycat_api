<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use App\Http\Requests\ThemeSetting\StoreThemeSettingRequest;
use App\Http\Requests\ThemeSetting\UpdateThemeSettingRequest;
use App\Models\Theme\ThemeSetting;

class ThemeSettingsController extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreThemeSettingRequest $request) {
        $themeSetting = ThemeSetting::create($request->validated());
        return response()->json($themeSetting, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ThemeSetting $theme_setting) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateThemeSettingRequest $request, ThemeSetting $theme_setting) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ThemeSetting $theme_setting) {
        //
    }
}
