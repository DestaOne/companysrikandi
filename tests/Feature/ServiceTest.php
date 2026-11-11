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
}
