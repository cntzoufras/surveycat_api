<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use UpdateUserRequest;

class UserController extends Controller {

    protected UserService $user_service;

    public function __construct(UserService $user_service) {
        $this->user_service = $user_service;
    }

    /**
     * Display the specified resource.
     *
     * @throws \Exception
     */
    public function show(Request $request): mixed {
        try {
            if (isset($request['id'])) {
                Validator::validate(['id' => $request['id']], [
                    'id' => 'uuid|required|exists:users,id',
                ]);
                return $this->user_service->show($request['id']);
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
    public function update(UpdateUserRequest $request, $id) {
        return $this->user_service->update($id, $request->validated());
    }
}
