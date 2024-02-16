<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Types\RoleType;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole(RoleType::ADMIN) ? true : false;
    }

    public function viewAnyDeleted(User $user): bool
    {
        return $user->hasRole(RoleType::ADMIN) ? true : false;
    }

    public function view(User $user, Order $order): bool
    {
        return $user->hasRole(RoleType::ADMIN) ? true : false;
    }

    public function destroy(User $user, Order $order): bool
    {
        return $user->hasRole(RoleType::ADMIN) ? true : false;
    }

    public function restore(User $user, Order $order): bool
    {
        return $user->hasRole(RoleType::ADMIN) ? true : false;
    }

    public function forceDelete(User $user, Order $order): bool
    {
        return $user->hasRole(RoleType::ADMIN) ? true : false;
    }
}
