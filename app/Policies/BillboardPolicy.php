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

    public function create(User $user): bool
    {
        if ($user->can(PermissionType::CAN_CREATE_BILLBOARD)) {
            return true;
        } else {
            return false;
        }
    }

    public function update(User $user, Billboard $billboard): bool
    {
        if ($user->can(PermissionType::CAN_UPDATE_BILLBOARD)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete(User $user, Billboard $billboard): bool
    {
        if ($user->can(PermissionType::CAN_DELETE_BILLBOARD)) {
            return true;
        } else {
            return false;
        }
    }
}
