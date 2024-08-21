<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use App\Http\Requests\VariablePalette\UpdateVariablePaletteRequest;
use App\Models\Theme\VariablePalette;
use App\Services\Theme\VariablePaletteService;

class VariablePalettesController extends Controller {

    protected VariablePaletteService $variable_palette_service;

    public function __construct(VariablePaletteService $variable_palette_service) {
        $this->variable_palette_service = $variable_palette_service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        $params = request()->all();
        $variable_palettes = $this->variable_palette_service->index($params);
        return response()->json($variable_palettes);
    }

    /**
     * Display the specified resource.
     */
    public function show(VariablePalette $variable_palette) {
        $variable_palette = $this->variable_palette_service->show($variable_palette->id);
        return response()->json($variable_palette);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVariablePaletteRequest $request, VariablePalette $variable_palette) {
        $params = $request->validated();
        $updated_variable_palette = $this->variable_palette_service->update($variable_palette, $params);
        return response()->json($updated_variable_palette);
    }

}

