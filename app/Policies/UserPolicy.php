<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Types\RoleType;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole(RoleType::ADMIN) ? true : false;
    }

    public function view(User $user, User $model): bool
    {
        return $user->hasRole(RoleType::ADMIN) ? true : false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(RoleType::ADMIN) ? true : false;
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasRole(RoleType::ADMIN) ? true : false;
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasRole(RoleType::ADMIN) ? true : false;
    }
}
