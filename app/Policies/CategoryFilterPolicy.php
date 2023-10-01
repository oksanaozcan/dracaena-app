<?php

namespace App\Policies;

use App\Models\CategoryFilter;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Types\PermissionType;

class CategoryFilterPolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CategoryFilter $categoryFilter): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->can(PermissionType::CAN_CREATE_CATEGORY_FILTER)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CategoryFilter $categoryFilter): bool
    {
        if ($user->can(PermissionType::CAN_UPDATE_CATEGORY_FILTER)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CategoryFilter $categoryFilter): bool
    {
        if ($user->can(PermissionType::CAN_DELETE_CATEGORY_FILTER)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Category $category): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Category $category): bool
    {
        //
    }
}
