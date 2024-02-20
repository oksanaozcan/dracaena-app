<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class AuthenticatedSessionControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $response = $this->post(route('logout'));

        $this->assertGuest();

        $response->assertSessionHasNoErrors();

        $this->assertNotNull(Session::token());

        $response->assertRedirect('/');
    }
}
