<?php

namespace App\Policies;

use App\Models\ProductGroupBySize;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Types\PermissionType;

class ProductGroupBySizePolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        if ($user->can(PermissionType::CAN_CREATE_PRODUCT_GROUP)) {
            return true;
        } else {
            return false;
        }
    }

    public function update(User $user, ProductGroupBySize $productGroupBySize): bool
    {
        if ($user->can(PermissionType::CAN_UPDATE_PRODUCT_GROUP)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete(User $user, ProductGroupBySize $productGroupBySize): bool
    {
        if ($user->can(PermissionType::CAN_DELETE_PRODUCT_GROUP)) {
            return true;
        } else {
            return false;
        }
    }
}
