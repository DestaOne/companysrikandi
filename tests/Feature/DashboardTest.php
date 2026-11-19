<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\Gallery;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_shows_counts_and_recent_messages(): void
    {
        Gallery::factory()->count(2)->create();
        Service::factory()->count(3)->create();
        ContactMessage::factory()->create(['subject' => 'Tanya harga genset']);
        ContactMessage::factory()->create(['read_at' => now()]);

        $this->actingAs(User::factory()->create(['name' => 'Budi']))
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Budi')
            ->assertSee('Tanya harga genset')
            ->assertViewHas('counts', fn (array $counts) => $counts['galleries'] === 2
                && $counts['unread_messages'] === 1
                && $counts['services'] === 3);
    }

    public function test_dashboard_requires_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_sidebar_groups_page_menus_under_content_category(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.dashboard'))
            ->assertSeeInOrder(['Overview', 'Konten', 'Home', 'Layanan', 'Tentang', 'Galeri']);
    }

    public function test_sidebar_bottom_has_view_website_in_new_tab_then_logout_and_header_has_no_home_link(): void
    {
        $html = $this->actingAs(User::factory()->create())->get(route('admin.dashboard'))->assertOk()->getContent();

        $this->assertStringNotContainsString('Halaman Utama', $html);
        $this->assertMatchesRegularExpression('/<a href="[^"]*"[^>]*target="_blank"[^>]*rel="noopener noreferrer"[^>]*>.*?Lihat Website/s', $html);

        $aside = substr($html, strpos($html, '<aside'), strpos($html, '</aside>') - strpos($html, '<aside'));
        $this->assertTrue(strpos($aside, 'Lihat Website') < strpos($aside, 'confirm-logout'));
    }

    public function test_logout_button_is_red_on_hover_and_asks_for_confirmation(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.dashboard'))
            ->assertSee('hover:bg-red-600', false)
            ->assertSee("\$dispatch('open-modal', 'confirm-logout')", false)
            ->assertSee('Keluar dari panel admin?')
            ->assertSee('Anda harus masuk lagi');
    }

    public function test_dashboard_lists_draft_services_linking_to_their_edit_form(): void
    {
        Service::factory()->create(['title' => 'Layanan Sudah Tayang']);
        $draft = Service::factory()->draft()->create(['title' => 'Layanan Masih Draft']);

        $html = $this->actingAs(User::factory()->create(['activities_seen_at' => now()->addMinute()]))
            ->get(route('admin.dashboard'))
            ->assertSee('Layanan Belum Terbit')
            ->assertSee('Layanan Masih Draft')
            ->assertSee(route('admin.services.edit', $draft), false)
            ->assertDontSee('Aktivitas Terakhir')
            ->getContent();

        $this->assertStringNotContainsString('Layanan Sudah Tayang', $html);
    }

    public function test_dashboard_says_so_when_all_services_are_published(): void
    {
        Service::factory()->create();

        $this->actingAs(User::factory()->create())
            ->get(route('admin.dashboard'))
            ->assertSee('Semua layanan sudah terbit.');
    }

    public function test_dashboard_lists_all_drafts_in_a_scrollable_card(): void
    {
        Service::factory()->draft()->count(8)->create();

        $this->actingAs(User::factory()->create())
            ->get(route('admin.dashboard'))
            ->assertSee('overflow-y-auto', false)
            ->assertDontSee('layanan draft lainnya');
    }
}
