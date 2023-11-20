<?php

namespace App\Policies;

use App\Models\Theme\ThemeVariable;
use App\Models\User;

class ThemeVariablePolicy {

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ThemeVariable $themeVariable): bool {
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
    public function update(User $user, ThemeVariable $themeVariable): bool {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ThemeVariable $themeVariable): bool {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ThemeVariable $themeVariable): bool {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ThemeVariable $themeVariable): bool {
        //
    }
}
