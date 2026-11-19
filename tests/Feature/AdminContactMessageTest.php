<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminContactMessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $message = ContactMessage::factory()->create();

        $this->get(route('admin.messages.index'))->assertRedirect(route('login'));
        $this->get(route('admin.messages.show', $message))->assertRedirect(route('login'));
        $this->delete(route('admin.messages.destroy', $message))->assertRedirect(route('login'));
    }

    public function test_index_lists_messages_with_unread_status(): void
    {
        ContactMessage::factory()->create(['name' => 'Budi Baru', 'read_at' => null]);
        ContactMessage::factory()->create(['name' => 'Sari Lama', 'read_at' => now()]);

        $this->actingAs(User::factory()->create())->get(route('admin.messages.index'))
            ->assertOk()
            ->assertSee('Budi Baru')
            ->assertSee('Sari Lama')
            ->assertSee('Baru')
            ->assertSee('Dibaca');
    }

    public function test_show_displays_details_reply_links_and_marks_as_read_quietly(): void
    {
        $message = ContactMessage::factory()->create([
            'name' => 'Budi', 'email' => 'budi@example.com', 'phone' => '0812-3456-7890',
            'subject' => 'Tanya harga', 'message' => 'Mohon info harga genset.',
        ]);
        $activityCount = ActivityLog::count();

        $this->actingAs(User::factory()->create())->get(route('admin.messages.show', $message))
            ->assertOk()
            ->assertSee('Mohon info harga genset.')
            ->assertSee('budi@example.com')
            ->assertSee('https://wa.me/6281234567890?', false)
            ->assertSee('mailto:budi@example.com?subject=Re%3A%20Tanya%20harga', false);

        $this->assertNotNull($message->fresh()->read_at);
        $this->assertSame($activityCount, ActivityLog::count());
    }

    public function test_admin_can_delete_message(): void
    {
        $message = ContactMessage::factory()->create();

        $this->actingAs(User::factory()->create())->delete(route('admin.messages.destroy', $message))
            ->assertRedirect(route('admin.messages.index'))
            ->assertSessionHas('success');

        $this->assertModelMissing($message);
    }

    public function test_bell_and_menu_show_unread_messages_and_drop_after_opening(): void
    {
        $admin = User::factory()->create(['activities_seen_at' => now()]);
        $message = ContactMessage::factory()->create(['name' => 'Tamu Baru']);
        $this->actingAs($admin);

        $this->get(route('admin.dashboard'))
            ->assertSee('1 pesan belum dibaca')
            ->assertSee('Tamu Baru')
            ->assertDontSee('aktivitas baru');

        $this->get(route('admin.messages.show', $message));

        $this->get(route('admin.dashboard'))->assertDontSee('pesan belum dibaca');
    }
}
