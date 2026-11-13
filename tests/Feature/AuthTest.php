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

        $this->post(route('logout'))->assertRedirect(route('login'));
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

    public function test_login_is_locked_after_three_wrong_attempts(): void
    {
        $user = User::factory()->create(['password' => 'rahasia123']);

        foreach (range(1, 3) as $i) {
            $this->post(route('login'), ['email' => $user->email, 'password' => 'salah'])
                ->assertSessionHasErrors('email');
        }

        // Percobaan ke-4 ditolak walau sandinya benar, sampai masa kunci habis
        $this->post(route('login'), ['email' => $user->email, 'password' => 'rahasia123'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->travel(4)->seconds();

        $this->post(route('login'), ['email' => $user->email, 'password' => 'rahasia123'])
            ->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_page_passes_remaining_lock_seconds(): void
    {
        $user = User::factory()->create();

        foreach (range(1, 3) as $i) {
            $this->from(route('login'))->post(route('login'), ['email' => $user->email, 'password' => 'salah']);
        }

        $this->withSession(['_old_input' => ['email' => $user->email]])
            ->get(route('login'))
            ->assertViewHas('lockoutSeconds', fn (int $seconds) => $seconds > 0);
    }

    public function test_idle_logout_redirects_to_login_with_message(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('logout'), ['idle' => 1])
            ->assertRedirect(route('login'))
            ->assertSessionHas('error');
        $this->assertGuest();
    }

    public function test_admin_pages_include_idle_logout_timer(): void
    {
        config(['auth.idle_timeout' => 600]);

        $this->actingAs(User::factory()->create())
            ->get(route('admin.dashboard'))
            ->assertSee('idleLogout(600)', false);
    }

    public function test_remember_me_skips_idle_logout_until_logout(): void
    {
        $user = User::factory()->create(['password' => 'rahasia123']);

        $this->post(route('login'), ['email' => $user->email, 'password' => 'rahasia123', 'remember' => 1]);
        $this->assertNotNull($user->fresh()->remember_token);
        $this->get(route('admin.dashboard'))->assertDontSee('idleLogout(', false);

        // Logout menghapus penanda dan token, login berikutnya tanpa centang kembali kena timer
        $this->post(route('logout'));
        $this->post(route('login'), ['email' => $user->email, 'password' => 'rahasia123']);
        $this->get(route('admin.dashboard'))->assertSee('idleLogout(', false);
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
