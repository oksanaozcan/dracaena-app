<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;
use Tests\TestHelper;
use Illuminate\Auth\Notifications\VerifyEmail;

class EmailVerificationNotificationControllerTest extends TestCase
{
    use RefreshDatabase;
    use TestHelper;

    public function test_1_it_verify_email_validates_user()
    {
        $notification = new VerifyEmail();
        $user = User::factory()->unverified()->create();

        $this->assertFalse($user->hasVerifiedEmail());

        $mail = $notification->toMail($user);
        $uri = $mail->actionUrl;

        $this->actingAs($user)
            ->get($uri);

        $this->assertTrue(User::find($user->id)->hasVerifiedEmail());
    }

    public function test_2_it_redirects_authenticated_users_to_dashboard_route_if_email_already_verified()
    {
        $user = $this->createUserWithRole('assistant');

        $this->actingAs($user)
            ->post(route('verification.send'))
            ->assertRedirect(route("dashboard"));
    }

    public function test_3_it_redirects_guests_to_login_page_when_sending_verification_notification()
    {
        $this->post(route('verification.send'))
            ->assertRedirect(route('login'));
    }
}
