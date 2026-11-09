<?php

namespace Tests\Feature;

use Tests\TestCase;

class NavbarTest extends TestCase
{
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

    public function test_service_menu_lists_company_services(): void
    {
        config(['company.services' => ['Layanan Uji Satu', 'Layanan Uji Dua']]);

        $this->get(route('home'))
            ->assertSee('Layanan Kami')
            ->assertSee('Lihat semua layanan')
            ->assertSee('Layanan Uji Satu')
            ->assertSee('Layanan Uji Dua');
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
