<?php

namespace App\Policies;

use App\Models\Favourite;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Types\RoleType;

class FavouritePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole(RoleType::ADMIN) ? true : false;
    }

    //TODO: change Cart model to Favourite
    // public function view(Customer $customer, Cart $cart): bool
    // {
    //    return $client->clerk_id === $cart->client_id;
    // }
}
