<?php

namespace App\Policies;

use App\Models\Habit;
use App\Models\User;

class HabitPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'guru', 'masteradmin']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Habit $habit): bool
    {
        // MasterAdmin can view all
        if ($user->role === 'masteradmin') {
            return true;
        }

        // Admin and Guru can only view their school's habits
        if (in_array($user->role, ['admin', 'guru'])) {
            return $user->school_id === $habit->school_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'masteradmin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Habit $habit): bool
    {
        // MasterAdmin can update all
        if ($user->role === 'masteradmin') {
            return true;
        }

        // Admin can only update their school's habits
        if ($user->role === 'admin') {
            return $user->school_id === $habit->school_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Habit $habit): bool
    {
        // MasterAdmin can delete all
        if ($user->role === 'masteradmin') {
            return true;
        }

        // Admin can only delete their school's habits
        if ($user->role === 'admin') {
            return $user->school_id === $habit->school_id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Habit $habit): bool
    {
        return $this->delete($user, $habit);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Habit $habit): bool
    {
        return $user->role === 'masteradmin';
    }
}