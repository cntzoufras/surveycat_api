<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use App\Http\Requests\ThemeVariable\StoreThemeVariableRequest;
use App\Http\Requests\ThemeVariable\UpdateThemeVariableRequest;
use App\Models\Theme\ThemeVariable;

class ThemeVariableController extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreThemeVariableRequest $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateThemeVariableRequest $request, ThemeVariable $themeVariable) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ThemeVariable $themeVariable) {
        //
    }
}
