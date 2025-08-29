<?php

namespace App\Policies;

use App\Models\Survey\Survey;
use App\Models\User;

class SurveyPolicy {

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool {
        // Any authenticated user can list; scoping is applied in repositories/controllers.
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Survey $survey): bool {
        return $survey->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool {
        // Any authenticated user can create their own survey.
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Survey $survey): bool {
        return $survey->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Survey $survey): bool {
        return $survey->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Survey $survey): bool {
        return $survey->user_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Survey $survey): bool {
        return $survey->user_id === $user->id;
    }
}
