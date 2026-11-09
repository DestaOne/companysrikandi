<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeTest extends TestCase
{
    public function test_home_shows_hero_with_title_and_description(): void
    {
        config([
            'home.hero.title' => 'Judul Hero Uji',
            'home.hero.description' => 'Deskripsi hero uji.',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Judul Hero Uji')
            ->assertSee('Deskripsi hero uji.')
            ->assertSee('images/home/hero.jpg', false);
    }

    public function test_hero_has_a_single_button_and_no_ornaments(): void
    {
        $html = $this->get(route('home'))->getContent();
        $hero = substr($html, strpos($html, '<section'), strpos($html, '</section>') - strpos($html, '<section'));

        $this->assertSame(1, substr_count($hero, '<a '));
        $this->assertStringNotContainsString('<svg', $hero);
    }

    public function test_home_lists_services_with_link_to_all_services(): void
    {
        $response = $this->get(route('home'));

        foreach (config('home.services.items') as $service) {
            $response->assertSee($service['title'])->assertSee($service['image'], false);
        }

        $response->assertSee(url('/layanan'), false);
    }

    public function test_service_carousel_has_four_services_and_a_see_more_card(): void
    {
        $this->assertCount(4, config('home.services.items'));

        $this->get(route('home'))
            ->assertSee('aria-roledescription="carousel"', false)
            ->assertSee('aria-label="Layanan sebelumnya"', false)
            ->assertSee('aria-label="Layanan berikutnya"', false)
            ->assertSee('Layanan sebelumnya')
            ->assertSee('Layanan berikutnya')
            ->assertSee('Lihat Selengkapnya');
    }

    public function test_carousel_duplicates_are_hidden_from_assistive_tech_and_tab_order(): void
    {
        $html = $this->get(route('home'))->getContent();

        $this->assertSame(8, substr_count($html, 'class="flex flex-none gap-6 pr-6"'));
        $this->assertSame(7, preg_match_all('/class="flex flex-none gap-6 pr-6"\s+aria-hidden="true"/', $html));
        $this->assertStringContainsString('tabindex="-1"', $html);
    }

    public function test_home_shows_three_features_in_order(): void
    {
        $features = config('home.features.items');

        $this->assertCount(3, $features);

        $this->get(route('home'))
            ->assertSeeInOrder(array_column($features, 'title'))
            ->assertSee('md:col-span-2', false);
    }

    public function test_home_ends_with_call_to_action(): void
    {
        $this->get(route('home'))
            ->assertSeeInOrder([config('home.features.items.2.title'), config('home.cta.title'), '<footer'], false)
            ->assertSee(url('/tentang-kami#kontak'), false);
    }

    public function test_all_home_images_exist(): void
    {
        $paths = [
            config('home.hero.image'),
            config('home.cta.image'),
            ...array_column(config('home.services.items'), 'image'),
            ...array_column(config('home.features.items'), 'image'),
        ];

        foreach ($paths as $path) {
            $this->assertFileExists(public_path($path));
        }
    }
}
