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
            ->assertSessionHas('mail_draft')
            ->assertSessionMissing('success');

        $this->assertDatabaseHas('contact_messages', ['email' => 'budi@example.com', 'subject' => 'Penawaran mesin']);
    }

    public function test_sending_message_offers_prefilled_email_to_company(): void
    {
        $this->post(route('about.contact.store'), $this->validPayload());

        $draft = session('mail_draft');
        $this->assertStringStartsWith('https://mail.google.com/mail/?view=cm&fs=1&to=', $draft['gmail']);
        $this->assertStringContainsString('su=Penawaran%20mesin', $draft['gmail']);
        $this->assertStringContainsString('Mohon%20info%20harga.', $draft['mailto']);
        $this->assertStringContainsString('081234567890', rawurldecode($draft['mailto']));

        $this->followingRedirects()->post(route('about.contact.store'), $this->validPayload())
            ->assertSee('Tidak dialihkan otomatis? Kirim manual lewat');
    }

    public function test_message_requires_valid_data(): void
    {
        $this->post(route('about.contact.store'), ['email' => 'salah', 'phone' => 'abc'])
            ->assertRedirect(route('about').'#kontak')
            ->assertSessionHasErrors(['name', 'email', 'phone', 'subject', 'message']);

        $this->assertSame(0, ContactMessage::count());
    }

    public function test_about_sections_have_line_and_root_decoration(): void
    {
        $html = $this->get(route('about'))->assertOk()->getContent();

        // Latar belakang, visi dan misi, serta kontak masing-masing punya satu hiasan
        $this->assertSame(3, substr_count($html, 'vector-effect="non-scaling-stroke" d="M500 0 C380 180'));
    }
}
