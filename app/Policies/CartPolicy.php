<?php

namespace App\Policies;

use App\Models\Cart;
use App\Models\Client;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Types\RoleType;

class CartPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        // Only authenticated admin users can view all carts
        return $user->hasRole(RoleType::ADMIN) ? true : false;
    }

    public function view(Client $client, Cart $cart): bool
    {
       return $client->clerk_id === $cart->client_id;
    }

    public function create(User $user): bool
    {
        // All authenticated clients can create carts
        return $user instanceof Client;
    }

    public function update(User $user, Cart $cart): bool
    {
        // Ensure the user can only update their own cart
        if ($user instanceof User) {
            return $user->id === $cart->user_id;
        } elseif ($user instanceof Client) {
            return $user->id === $cart->client_id;
        }

        return false;
    }

    public function delete(User $user, Cart $cart): bool
    {
        // Ensure the user can only delete their own cart
        if ($user instanceof User) {
            return $user->id === $cart->user_id;
        } elseif ($user instanceof Client) {
            return $user->id === $cart->client_id;
        }

        return false;
    }
}
