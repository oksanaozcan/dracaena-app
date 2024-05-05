<?php

namespace App\Policies;

use App\Models\Favourite;
use App\Models\Client;
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

    public function view(Client $client, Cart $cart): bool
    {
       return $client->clerk_id === $cart->client_id;
    }
}
