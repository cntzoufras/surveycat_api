<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use App\Http\Requests\VariablePalette\StoreVariablePaletteRequest;
use App\Http\Requests\VariablePalette\UpdateVariablePaletteRequest;
use App\Models\Theme\VariablePalette;

class VariablePalettesController extends Controller {

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
    public function store(StoreVariablePaletteRequest $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(VariablePalette $variable_palette) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VariablePalette $variable_palette) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVariablePaletteRequest $request, VariablePalette $variable_palette) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VariablePalette $variable_palette) {
        //
    }
}
