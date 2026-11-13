<?php

namespace Tests\Feature;

use Tests\TestCase;

class ServiceTest extends TestCase
{
    public function test_service_page_lists_all_services(): void
    {
        $response = $this->get(route('services'))->assertOk();

        foreach (config('service.items') as $service) {
            $response->assertSee($service['title']);
        }
    }

    public function test_service_page_uses_expected_url(): void
    {
        $this->assertSame(url('/layanan'), route('services'));
    }

    public function test_service_detail_shows_title_and_products(): void
    {
        $service = config('service.items.0');

        $this->get(route('services.show', $service['slug']))
            ->assertOk()
            ->assertSee($service['title'])
            ->assertSee($service['products'][0]);
    }

    public function test_unknown_service_returns_not_found(): void
    {
        $this->get('/layanan/tidak-ada')->assertNotFound();
    }
}
