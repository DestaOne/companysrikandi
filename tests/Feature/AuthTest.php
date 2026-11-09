<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_shown_to_guests(): void
    {
        $this->get(route('login'))->assertOk()->assertSee('Masuk');
    }

    public function test_user_can_login_and_logout(): void
    {
        $user = User::factory()->create(['password' => 'rahasia123']);

        $this->post(route('login'), ['email' => $user->email, 'password' => 'rahasia123'])
            ->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))->assertRedirect(route('home'));
        $this->assertGuest();
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = User::factory()->create();

        $this->from(route('login'))
            ->post(route('login'), ['email' => $user->email, 'password' => 'salah'])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_routes_require_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('admin.galleries.index'))->assertRedirect(route('login'));
    }

    public function test_register_and_password_reset_routes_do_not_exist(): void
    {
        $this->get('/register')->assertNotFound();
        $this->get('/forgot-password')->assertNotFound();
    }
}
