<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreThemeSettingsRequest;
use App\Http\Requests\UpdateThemeSettingsRequest;
use App\Models\ThemeSettings;

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
    public function store(StoreThemeSettingsRequest $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ThemeSettings $theme_settings) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ThemeSettings $theme_settings) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateThemeSettingsRequest $request, ThemeSettings $theme_settings) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ThemeSettings $theme_settings) {
        //
    }
}
