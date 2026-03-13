<?php

namespace App\Policies;

use App\Models\Plat;
use App\Models\User;

class PlatPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Plat $plat): bool
    {
        return $user->id === $plat->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Plat $plat): bool
    {
        return $user->id === $plat->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Plat $plat): bool
    {
        return $user->id === $plat->user_id;
    }
}