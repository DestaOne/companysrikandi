<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\Gallery;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_shows_counts_and_recent_messages(): void
    {
        Gallery::factory()->count(2)->create();
        ContactMessage::factory()->create(['subject' => 'Tanya harga genset']);
        ContactMessage::factory()->create(['read_at' => now()]);

        $this->actingAs(User::factory()->create(['name' => 'Budi']))
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Budi')
            ->assertSee('Tanya harga genset')
            ->assertViewHas('counts', fn (array $counts) => $counts['galleries'] === 2
                && $counts['unread_messages'] === 1
                && $counts['services'] === count(config('service.items')));
    }

    public function test_dashboard_requires_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }
}
