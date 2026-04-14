<?php

namespace App\Policies;

use App\Models\HabitRule;
use App\Models\User;

class HabitRulePolicy
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
    public function view(User $user, HabitRule $rule): bool
    {
        // MasterAdmin can view all
        if ($user->role === 'masteradmin') {
            return true;
        }

        // Admin and Guru can view global rules or their school's rules
        if (in_array($user->role, ['admin', 'guru'])) {
            return is_null($rule->school_id) || $user->school_id === $rule->school_id;
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
    public function update(User $user, HabitRule $rule): bool
    {
        // MasterAdmin can update all
        if ($user->role === 'masteradmin') {
            return true;
        }

        // Admin can only update their school's rules
        if ($user->role === 'admin') {
            // Can't update global rules (school_id is null)
            if (is_null($rule->school_id)) {
                return false;
            }

            return $user->school_id === $rule->school_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, HabitRule $rule): bool
    {
        // MasterAdmin can delete all
        if ($user->role === 'masteradmin') {
            return true;
        }

        // Admin can only delete their school's rules
        if ($user->role === 'admin') {
            // Can't delete global rules
            if (is_null($rule->school_id)) {
                return false;
            }

            return $user->school_id === $rule->school_id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, HabitRule $rule): bool
    {
        return $this->delete($user, $rule);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, HabitRule $rule): bool
    {
        return $user->role === 'masteradmin';
    }
}