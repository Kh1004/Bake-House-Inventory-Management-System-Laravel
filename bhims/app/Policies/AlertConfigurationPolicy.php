<?php

namespace App\Policies;

use App\Models\AlertConfiguration;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AlertConfigurationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Any authenticated user can view their own alert configurations
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AlertConfiguration $alertConfiguration): bool
    {
        return $user->id === $alertConfiguration->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Any authenticated user can create alert configurations
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AlertConfiguration $alertConfiguration): bool
    {
        return $user->id === $alertConfiguration->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AlertConfiguration $alertConfiguration): bool
    {
        return $user->id === $alertConfiguration->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AlertConfiguration $alertConfiguration): bool
    {
        return $user->id === $alertConfiguration->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AlertConfiguration $alertConfiguration): bool
    {
        return $user->id === $alertConfiguration->user_id;
    }
}
