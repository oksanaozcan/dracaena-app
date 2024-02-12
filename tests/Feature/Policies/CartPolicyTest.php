<?php

namespace Tests\Feature\Policies;

use Tests\TestCase;
use App\Models\Cart;
use App\Policies\CartPolicy;
use Tests\TestHelper;

class CartPolicyTest extends TestCase
{
    use TestHelper;

    public function test_1_admin_can_view_any_cart()
    {
        $user = $this->createUserWithRole('admin');
        $policy = new CartPolicy();

        $this->assertTrue($policy->viewAny($user));
    }

    public function test_2_manager_can_not_view_any_cart()
    {
        $user = $this->createUserWithRole('manager');
        $policy = new CartPolicy();

        $this->assertFalse($policy->viewAny($user));
    }

    public function test_3_assistant_can_not_view_any_cart()
    {
        $user = $this->createUserWithRole('assistant');
        $policy = new CartPolicy();

        $this->assertFalse($policy->viewAny($user));
    }

    public function test_4_client_can_view_cart()
    {
        $client = $this->createClient();
        $cart = Cart::factory()->create(['client_id' => $client->clerk_id]);

        $policy = new CartPolicy();

        $this->assertTrue($policy->view($client, $cart));
    }
}
