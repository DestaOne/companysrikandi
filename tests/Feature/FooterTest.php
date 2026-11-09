<?php

namespace Tests\Feature;

use Tests\TestCase;

class FooterTest extends TestCase
{
    public function test_footer_shows_logo_description_contact_and_services(): void
    {
        config([
            'company.name' => 'PT. Contoh Nusantara',
            'company.description' => 'Deskripsi uji perusahaan.',
            'company.footer_services' => ['Layanan Uji Satu', 'Layanan Uji Dua'],
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('<footer', false)
            ->assertSee('Deskripsi uji perusahaan.')
            ->assertSeeInOrder(['Kontak', 'Layanan', 'Layanan Uji Satu', 'Layanan Uji Dua'])
            ->assertSee('&copy; '.now()->year, false);
    }

    public function test_company_data_from_config_is_shown_with_phone_and_email_links(): void
    {
        $this->get(route('home'))
            ->assertSee('Jalan Scarlet 3 Nomor 9')
            ->assertSee('href="tel:081133361090"', false)
            ->assertSee('href="mailto:dwisrikandinusantara@gmail.com"', false);
    }

    public function test_footer_lists_placeholder_services_by_default(): void
    {
        $this->get(route('home'))
            ->assertSeeInOrder(['Layanan 1', 'Layanan 2', 'Layanan 3', 'Layanan 4']);
    }

    public function test_phone_with_separators_becomes_link(): void
    {
        config(['company.phone' => '+62 812-3456-7890']);

        $this->get(route('home'))
            ->assertSee('href="tel:+6281234567890"', false);
    }

    public function test_placeholder_contacts_are_not_linked(): void
    {
        config([
            'company.phone' => '[Nomor telepon]',
            'company.email' => '[Alamat email]',
        ]);

        $this->get(route('home'))
            ->assertDontSee('href="tel:', false)
            ->assertDontSee('href="mailto:', false);
    }

    public function test_operating_hours_row_is_hidden_when_empty(): void
    {
        config(['company.hours' => null]);

        $this->get(route('home'))->assertDontSee('Jam operasional');

        config(['company.hours' => 'Senin sampai Jumat, 08.00 sampai 17.00']);

        $this->get(route('home'))->assertSee('Senin sampai Jumat, 08.00 sampai 17.00');
    }
}
