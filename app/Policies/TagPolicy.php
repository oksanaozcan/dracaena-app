<?php

namespace App\Policies;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Types\PermissionType;

class TagPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        if ($user->can(PermissionType::CAN_CREATE_TAG)) {
            return true;
        } else {
            return false;
        }
    }

    public function update(User $user, Tag $tag): bool
    {
        if ($user->can(PermissionType::CAN_UPDATE_TAG)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete(User $user, Tag $tag): bool
    {
        if ($user->can(PermissionType::CAN_DELETE_TAG)) {
            return true;
        } else {
            return false;
        }
    }
}
