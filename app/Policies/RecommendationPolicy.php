<?php

namespace App\Policies;

use App\Models\Recommendation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RecommendationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Users can view their own recommendations
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Recommendation $recommendation): bool
    {
        // Users can only view their own recommendations, admins can view all
        return $user->role === 'admin' || $recommendation->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // All authenticated users can create recommendations
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Recommendation $recommendation): bool
    {
        // Users cannot update recommendations (they are system-generated)
        // Only admins can update for maintenance purposes
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Recommendation $recommendation): bool
    {
        // Users can delete their own recommendations, admins can delete all
        return $user->role === 'admin' || $recommendation->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Recommendation $recommendation): bool
    {
        // Only admins can restore recommendations
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Recommendation $recommendation): bool
    {
        // Only admins can force delete recommendations
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can analyze a plat for recommendations.
     */
    public function analyze(User $user): bool
    {
        // All authenticated users can request plat analysis
        return true;
    }
}
