<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Types\PermissionType;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        if ($user->can(PermissionType::CAN_CREATE_CATEGORY)) {
            return true;
        } else {
            return false;
        }
    }

    public function update(User $user, Category $category): bool
    {
        if ($user->can(PermissionType::CAN_UPDATE_CATEGORY)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete(User $user, Category $category): bool
    {
        if ($user->can(PermissionType::CAN_DELETE_CATEGORY)) {
            return true;
        } else {
            return false;
        }
    }
}
