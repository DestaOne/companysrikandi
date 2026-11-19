<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\ContactMessage;
use App\Models\Gallery;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_open_activity_page(): void
    {
        $this->get(route('admin.activities.index'))->assertRedirect(route('login'));
    }

    public function test_page_shows_admin_name_activity_date_and_time_in_company_timezone(): void
    {
        $admin = User::factory()->create(['name' => 'Siti Admin']);
        $this->actingAs($admin);

        $service = Service::factory()->create(['title' => 'Mesin Industri']);
        $this->travelTo('2026-11-17 03:05:09'); // UTC; WIB = 10:05:09
        $service->update(['title' => 'Mesin Industri Baru']);
        $this->travelBack();

        $this->get(route('admin.activities.index'))
            ->assertOk()
            ->assertSee('Siti Admin')
            ->assertSee('Menambah layanan “Mesin Industri”')
            ->assertSee('Mengubah layanan “Mesin Industri Baru”')
            ->assertSee('Judul')
            ->assertSee('17 November 2026')
            ->assertSee('10:05:09 WIB');
    }

    public function test_deleted_item_keeps_its_name_in_the_log(): void
    {
        $this->actingAs(User::factory()->create());
        $gallery = Gallery::factory()->create(['title' => 'Foto Gudang']);
        $gallery->delete();

        $this->get(route('admin.activities.index'))->assertSee('Menghapus gambar galeri “Foto Gudang”');
    }

    public function test_login_and_logout_are_recorded(): void
    {
        $admin = User::factory()->create(['name' => 'Budi Admin', 'email' => 'budi@example.com']);

        $this->post(route('login'), ['email' => 'budi@example.com', 'password' => 'password']);
        $this->post(route('logout'));

        $this->assertDatabaseHas('activity_logs', ['user_id' => $admin->id, 'action' => 'login']);
        $this->assertDatabaseHas('activity_logs', ['user_id' => $admin->id, 'action' => 'logout']);

        $this->actingAs($admin)->get(route('admin.activities.index'))
            ->assertSee('Masuk ke panel admin')
            ->assertSee('Keluar dari panel admin');
    }

    public function test_visitor_message_is_listed_as_visitor_activity(): void
    {
        ContactMessage::factory()->create(['name' => 'Tamu Web']);

        $this->actingAs(User::factory()->create())->get(route('admin.activities.index'))
            ->assertSee('Pengunjung')
            ->assertSee('Menerima pesan masuk dari “Tamu Web”');
    }

    public function test_page_lists_newest_first_and_sidebar_has_activity_menu(): void
    {
        $this->actingAs(User::factory()->create());
        ActivityLog::create(['action' => 'created', 'subject_type' => Gallery::class, 'subject_id' => 1, 'subject_label' => 'Pertama']);
        ActivityLog::create(['action' => 'created', 'subject_type' => Gallery::class, 'subject_id' => 2, 'subject_label' => 'Kedua']);

        $this->get(route('admin.activities.index'))
            ->assertSeeInOrder(['Kedua', 'Pertama'])
            ->assertSee(route('admin.activities.index'), false);
    }

    public function test_columns_are_admin_time_activity_page_and_page_is_shown_per_menu(): void
    {
        $this->actingAs(User::factory()->create());
        Gallery::factory()->create();
        $this->put(route('admin.home-sections.update', 'about-vision'), ['title' => 'Visi', 'text' => 'Baru.']);

        $response = $this->get(route('admin.activities.index'))
            ->assertSeeInOrder(['Admin', 'Waktu', 'Aktivitas', 'Halaman diubah']);

        $this->assertSame(['Tentang', 'Galeri'], ActivityLog::orderByDesc('id')->get()->pluck('page')->all());
        $response->assertSee('Tentang')->assertSee('Galeri');
    }

    public function test_bell_shows_red_count_of_new_activities_and_resets_after_opening_activity_page(): void
    {
        $admin = User::factory()->create(['activities_seen_at' => now()->subMinute()]);
        $this->actingAs($admin);

        $this->get(route('admin.dashboard'))->assertDontSee('aktivitas baru');

        Service::factory()->count(3)->create();

        $this->get(route('admin.dashboard'))
            ->assertSee('3 aktivitas baru')
            ->assertSee('bg-red-600', false)
            ->assertSee('menambah layanan');

        $this->get(route('admin.activities.index'))->assertDontSee('aktivitas baru');
        $this->get(route('admin.dashboard'))->assertDontSee('aktivitas baru');
    }

    public function test_login_and_logout_are_not_counted_as_new_activities(): void
    {
        $admin = User::factory()->create(['activities_seen_at' => now()->subMinute()]);
        ActivityLog::create(['user_id' => $admin->id, 'action' => 'login', 'subject_type' => User::class, 'subject_id' => $admin->id]);

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertDontSee('aktivitas baru');
    }

    public function test_bell_caps_large_counts_at_99_plus(): void
    {
        $admin = User::factory()->create(['activities_seen_at' => now()->subMinute()]);
        ActivityLog::insert(array_fill(0, 100, [
            'action' => 'created', 'subject_type' => Service::class, 'subject_id' => 1, 'created_at' => now(), 'updated_at' => now(),
        ]));

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertSee('99+');
    }

    public function test_bell_preview_lists_only_five_newest_and_mentions_the_rest(): void
    {
        $admin = User::factory()->create(['activities_seen_at' => now()->subMinute()]);
        $this->actingAs($admin);
        Service::factory()->count(7)->create();

        $this->get(route('admin.dashboard'))
            ->assertSee('7 aktivitas baru')
            ->assertSee('dan 2 aktivitas baru lainnya');
    }

    public function test_opening_bell_marks_activities_as_seen(): void
    {
        $admin = User::factory()->create(['activities_seen_at' => now()->subMinute()]);
        $this->actingAs($admin);
        Service::factory()->count(2)->create();

        $this->post(route('admin.activities.seen'))->assertNoContent();

        $this->get(route('admin.dashboard'))->assertDontSee('aktivitas baru');
    }

    public function test_guest_cannot_mark_activities_as_seen(): void
    {
        $this->post(route('admin.activities.seen'))->assertRedirect(route('login'));
    }
}
