<?php

namespace App\Policies;

use App\Models\Survey\SurveyCategory;
use App\Models\User;

class SurveyCategoryPolicy {

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SurveyCategory $survey_category): bool {
        $allowed_roles = ['admin', 'premium', 'registered', 'guest'];
        return in_array($user->role, $allowed_roles);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SurveyCategory $survey_category): bool {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SurveyCategory $survey_category): bool {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SurveyCategory $survey_category): bool {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SurveyCategory $survey_category): bool {
        return $user->role === 'admin';
    }
}
