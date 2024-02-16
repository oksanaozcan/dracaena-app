<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Types\PermissionType;

class ProductPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        if ($user->can(PermissionType::CAN_CREATE_PRODUCT)) {
            return true;
        } else {
            return false;
        }
    }

    public function update(User $user, Product $product): bool
    {
        if ($user->can(PermissionType::CAN_UPDATE_PRODUCT)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete(User $user, Product $product): bool
    {
        if ($user->can(PermissionType::CAN_DELETE_PRODUCT)) {
            return true;
        } else {
            return false;
        }
    }
}
