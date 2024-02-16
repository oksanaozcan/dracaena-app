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

    public function create(User $user): bool
    {
        if ($user->can(PermissionType::CAN_CREATE_CATEGORY_FILTER)) {
            return true;
        } else {
            return false;
        }
    }

    public function update(User $user, CategoryFilter $categoryFilter): bool
    {
        if ($user->can(PermissionType::CAN_UPDATE_CATEGORY_FILTER)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete(User $user, CategoryFilter $categoryFilter): bool
    {
        if ($user->can(PermissionType::CAN_DELETE_CATEGORY_FILTER)) {
            return true;
        } else {
            return false;
        }
    }
}
