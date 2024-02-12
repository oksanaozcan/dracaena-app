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

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Only authenticated admin users can view all carts
        return $user->hasRole(RoleType::ADMIN) ? true : false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Client $client, Cart $cart): bool
    {
       return $client->clerk_id === $cart->client_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // All authenticated clients can create carts
        return $user instanceof Client;
    }

    /**
     * Determine whether the user can update the model.
     */
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

    /**
     * Determine whether the user can delete the model.
     */
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
