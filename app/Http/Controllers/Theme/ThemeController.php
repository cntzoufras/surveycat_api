<?php

namespace App\Http\Controllers\Theme;

use App\Http\Controllers\Controller;
use App\Http\Requests\Theme\StoreThemeRequest;
use App\Http\Requests\Theme\UpdateThemeRequest;
use App\Models\Theme\Theme;
use App\Services\Theme\ThemeService;
use Illuminate\Http\Request;
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
    public function index(Request $request) {
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
    public function show(Request $request) {
        try {
            if (isset($request['id'])) {
                Validator::validate(['id' => $request['id']], [
                    'id' => 'uuid|required|exists:themes,id',
                ]);
                return $this->theme_service->show($request['id']);
            }
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        }
        return null;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateThemeRequest $request, Theme $theme) {
        return $this->theme_service->update($theme, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Theme $theme) {
        return $this->theme_service->delete($theme);
    }
}
