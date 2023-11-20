<?php

namespace App\Policies;

use App\Models\Survey\SurveyQuestionChoice;
use App\Models\User;

class SurveyQuestionChoicePolicy {

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SurveyQuestionChoice $surveyQuestionChoice): bool {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SurveyQuestionChoice $surveyQuestionChoice): bool {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SurveyQuestionChoice $surveyQuestionChoice): bool {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SurveyQuestionChoice $surveyQuestionChoice): bool {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SurveyQuestionChoice $surveyQuestionChoice): bool {
        //
    }
}
