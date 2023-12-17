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
     * Show the form for creating a new resource.
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreThemeSettingRequest $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ThemeSetting $theme_setting) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ThemeSetting $theme_setting) {
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
