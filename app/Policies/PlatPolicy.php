<?php

namespace App\Policies;

use App\Models\Plat;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PlatPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view plats
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Plat $plat): bool
    {
        // All authenticated users can view individual plats
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admins can create plats
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Plat $plat): bool
    {
        // Only admins can update plats
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Plat $plat): bool
    {
        // Only admins can delete plats
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Plat $plat): bool
    {
        // Only admins can restore plats
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Plat $plat): bool
    {
        // Only admins can force delete plats
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can upload images for the plat.
     */
    public function uploadImage(User $user, Plat $plat): bool
    {
        // Only admins can upload plat images
        return $user->role === 'admin';
    }
}
