<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FooterTest extends TestCase
{
    use RefreshDatabase;

    public function test_footer_shows_logo_description_contact_and_services(): void
    {
        config([
            'company.name' => 'PT. Contoh Nusantara',
            'company.description' => 'Deskripsi uji perusahaan.',
        ]);

        Service::factory()->create(['title' => 'Layanan Uji Satu']);
        Service::factory()->create(['title' => 'Layanan Uji Dua']);

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

    public function test_footer_lists_only_published_services_linking_to_their_page(): void
    {
        $published = Service::factory()->create(['title' => 'Layanan Terbit']);
        Service::factory()->draft()->create(['title' => 'Layanan Draft']);

        $this->get(route('home'))
            ->assertSee('Layanan Terbit')
            ->assertSee(route('services.show', $published->slug), false)
            ->assertDontSee('Layanan Draft');
    }

    public function test_footer_tells_when_there_are_no_services(): void
    {
        $this->get(route('home'))->assertSee('Belum ada layanan.');
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

    public function test_footer_contact_follows_contact_edited_in_admin_about_menu(): void
    {
        $this->actingAs(User::factory()->create())
            ->put(route('admin.home-sections.update', 'about-contact'), [
                'eyebrow' => 'Kontak', 'title' => 'Hubungi Kami', 'description' => 'Siap membantu.',
                'address' => 'Jalan Baru Nomor 7, Tangerang',
                'phone' => '081299998888',
                'email' => 'baru@contoh.test',
            ]);
        auth()->logout();

        foreach ([route('home'), route('about'), route('galleries.index')] as $url) {
            $this->get($url)
                ->assertSee('Jalan Baru Nomor 7, Tangerang')
                ->assertSee('href="tel:081299998888"', false)
                ->assertSee('href="mailto:baru@contoh.test"', false)
                ->assertDontSee('Jalan Scarlet 3 Nomor 9');
        }

    }

    public function test_footer_services_flow_in_columns_of_five(): void
    {
        Service::factory()->count(9)->create();

        $this->get(route('home'))
            ->assertSee('md:grid-rows-5', false)
            ->assertSee('md:grid-flow-col', false);
    }
}
