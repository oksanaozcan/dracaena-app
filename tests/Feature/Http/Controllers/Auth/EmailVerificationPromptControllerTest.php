<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class EmailVerificationPromptControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirect_to_intended_route_if_email_verified()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $this->actingAs($user);

        $request = Request::create('/verify-email', 'GET');
        $request->setUserResolver(fn() => $user);

        $controller = new EmailVerificationPromptController();
        $response = $controller->__invoke($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(url(RouteServiceProvider::HOME), $response->getTargetUrl());
    }
}
