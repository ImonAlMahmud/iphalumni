<?php

namespace Tests\Feature;

use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    /**
     * Test that the bilingual translation helper handles legacy strings safely.
     */
    public function test_bilingual_helper_handles_string_replacements(): void
    {
        // Default Bengali locale
        $resultBn = __('লগআউট', 'Logout');
        $this->assertEquals('লগআউট', $resultBn);

        // English session locale
        session(['locale' => 'en']);
        $resultEn = __('লগআউট', 'Logout');
        $this->assertEquals('Logout', $resultEn);
        session()->forget('locale');
    }

    /**
     * Test that GET /logout is disabled and returns 405 Method Not Allowed.
     */
    public function test_logout_get_is_disallowed(): void
    {
        $response = $this->get('/logout');
        $this->assertTrue(in_array($response->status(), [404, 405]));
    }

    /**
     * Test that UddoktaPay webhook requires valid HMAC-SHA256 signature when secret is configured.
     */
    public function test_uddoktapay_webhook_rejects_invalid_signature(): void
    {
        config(['services.uddoktapay.webhook_secret' => 'test-secret-key']);

        $payload = json_encode(['invoice_id' => 'INV-12345', 'status' => 'COMPLETED']);
        $response = $this->call(
            'POST',
            '/webhook/uddoktapay',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_RT_SIGNATURE' => 'invalid-hmac-signature',
            ],
            $payload
        );

        $response->assertStatus(401);
    }

    /**
     * Test that Admin login redirects to member portal (/portal) instead of admin panel.
     */
    public function test_admin_login_redirects_to_portal(): void
    {
        $guard = \Mockery::mock(\Illuminate\Contracts\Auth\StatefulGuard::class);
        $user = new \App\Models\User([
            'id' => 999999,
            'name' => 'Test Admin',
            'email' => 'admin_test_unit@example.com',
            'role' => 'admin',
            'status' => 'active',
        ]);
        $guard->shouldReceive('attempt')->once()->andReturn(true);
        $guard->shouldReceive('user')->andReturn($user);
        \Illuminate\Support\Facades\Auth::swap($guard);

        $request = \Illuminate\Http\Request::create('/login', 'POST', [
            'email' => 'admin_test_unit@example.com',
            'password' => 'secret123',
        ]);
        $request->setLaravelSession($this->app['session.store']);

        $controller = new \App\Http\Controllers\AuthController();
        $response = $controller->login($request);

        $this->assertEquals(url('/portal'), $response->getTargetUrl());
    }
}
