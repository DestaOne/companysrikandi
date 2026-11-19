<?php

namespace Tests\Feature;

use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavbarTest extends TestCase
{
    use RefreshDatabase;

    public function test_navbar_shows_logo_menu_and_contact_button(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('x-data="navbar"', false)
            ->assertSee('images/logo.png', false)
            ->assertSee('images/logo-light.png', false)
            ->assertSee('data-solid="false"', false)
            ->assertSeeInOrder(['Beranda', 'Layanan', 'Tentang', 'Galeri', 'Hubungi Kami']);
    }

    public function test_contact_button_points_to_contact_section(): void
    {
        $this->get(route('home'))
            ->assertSee(url('/tentang-kami#kontak'), false);
    }

    public function test_current_page_is_marked_in_menu(): void
    {
        $this->get(route('home'))
            ->assertSee('aria-current="page"', false);
    }

    public function test_service_menu_follows_published_services_and_links_to_their_detail(): void
    {
        $satu = Service::factory()->create(['title' => 'Layanan Uji Satu']);
        Service::factory()->draft()->create(['title' => 'Layanan Masih Draft']);

        $this->get(route('home'))
            ->assertSee('Layanan Kami')
            ->assertSee('Lihat semua layanan')
            ->assertSee('Layanan Uji Satu')
            ->assertSee('href="'.route('services.show', $satu->slug).'"', false)
            ->assertDontSee('Layanan Masih Draft');

        Service::factory()->create(['title' => 'Layanan Uji Dua']);

        $this->get(route('home'))->assertSee('Layanan Uji Dua');
    }

    public function test_navbar_has_no_contact_details(): void
    {
        config([
            'company.phone' => '081133361090',
            'company.email' => 'halo@contoh.co.id',
        ]);

        $html = $this->get(route('home'))->getContent();
        $navbar = substr($html, strpos($html, '<header'), strpos($html, '</header>') - strpos($html, '<header'));

        $this->assertStringNotContainsString('tel:', $navbar);
        $this->assertStringNotContainsString('mailto:', $navbar);
        $this->assertStringNotContainsString('081133361090', $navbar);
    }
}
