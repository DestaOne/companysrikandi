<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlashMessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_success_message_is_shown_as_popup_with_green_check_and_closes_itself(): void
    {
        $this->actingAs(User::factory()->create())
            ->withSession(['success' => 'Layanan berhasil ditambahkan.'])
            ->get(route('admin.dashboard'))
            ->assertSee('role="alertdialog"', false)
            ->assertSee('Berhasil')
            ->assertSee('Layanan berhasil ditambahkan.')
            ->assertSee('M15 27l8 8 14-16', false)
            ->assertSee('setTimeout(() => show = false', false);
    }

    public function test_error_message_is_shown_as_popup_with_red_cross_and_stays_until_closed(): void
    {
        $this->actingAs(User::factory()->create())
            ->withSession(['error' => 'Gagal menyimpan layanan.'])
            ->get(route('admin.dashboard'))
            ->assertSee('Gagal menyimpan layanan.')
            ->assertSee('M18 18l16 16', false)
            ->assertDontSee('setTimeout(() => show = false', false)
            ->assertDontSee('M15 27l8 8 14-16', false);
    }

    public function test_no_popup_without_message_and_public_pages_use_it_too(): void
    {
        $this->get(route('about'))->assertDontSee('role="alertdialog"', false);

        $this->withSession(['success' => 'Pesan Anda terkirim.'])
            ->get(route('about'))
            ->assertSee('role="alertdialog"', false)
            ->assertSee('Pesan Anda terkirim.');
    }
}
