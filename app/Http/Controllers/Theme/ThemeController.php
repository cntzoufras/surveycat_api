<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use App\Http\Requests\Theme\StoreThemeRequest;
use App\Http\Requests\Theme\UpdateThemeRequest;
use App\Models\Theme\Theme;

class ThemeController extends Controller {

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
    public function store(StoreThemeRequest $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Theme $theme) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Theme $theme) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateThemeRequest $request, Theme $theme) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Theme $theme) {
        //
    }
}
