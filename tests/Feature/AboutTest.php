<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, string>
     */
    private function validPayload(): array
    {
        return [
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'phone' => '081234567890',
            'subject' => 'Penawaran mesin',
            'message' => 'Mohon info harga.',
        ];
    }

    public function test_about_page_shows_sections_and_form(): void
    {
        $this->get(route('about'))
            ->assertOk()
            ->assertSee('Latar Belakang')
            ->assertSee('Visi')
            ->assertSee('Misi')
            ->assertSee('id="kontak"', false)
            ->assertSee('No. Telepon / WhatsApp');
    }

    public function test_visitor_can_send_message(): void
    {
        $this->post(route('about.contact.store'), $this->validPayload())
            ->assertRedirect(route('about').'#kontak')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', ['email' => 'budi@example.com', 'subject' => 'Penawaran mesin']);
    }

    public function test_message_requires_valid_data(): void
    {
        $this->post(route('about.contact.store'), ['email' => 'salah', 'phone' => 'abc'])
            ->assertSessionHasErrors(['name', 'email', 'phone', 'subject', 'message']);

        $this->assertSame(0, ContactMessage::count());
    }
}
