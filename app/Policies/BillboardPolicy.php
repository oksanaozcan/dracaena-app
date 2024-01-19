<?php

namespace App\Policies;

use App\Models\Billboard;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Types\PermissionType;
use App\Types\RoleType;

class BillboardPolicy
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
    public function view(User $user, Billboard $billboard): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->can(PermissionType::CAN_CREATE_BILLBOARD)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Billboard $billboard): bool
    {
        if ($user->can(PermissionType::CAN_UPDATE_BILLBOARD)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Billboard $billboard): bool
    {
        if ($user->can(PermissionType::CAN_DELETE_BILLBOARD)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Billboard $billboard): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Billboard $billboard): bool
    {
        //
    }
}
