<?php

namespace Tests\Feature\Controllers\Auth;

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;

class RegisteredUserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_1_it_redirects_from_register_route_to_login_form()
    {
        $response = $this->get('register');
        $response->assertRedirect(route('login'));
    }

    public function test_2_it_creates_a_new_user()
    {
        Event::fake();

        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->post('/register', $userData);

        $this->assertTrue(User::where('email', 'john@example.com')->exists());
        Event::assertDispatched(Registered::class);
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_3_it_hashes_the_user_password()
    {
        $userData = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->post('/register', $userData);

        $this->assertTrue(Hash::check('password', User::where('email', 'jane@example.com')->value('password')));
    }
}
