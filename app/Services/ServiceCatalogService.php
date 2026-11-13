<?php

namespace App\Services;

class ServiceCatalogService
{
    /**
     * Isi halaman Layanan (banner, intro, daftar, CTA).
     *
     * @return array<string, mixed>
     */
    public function page(): array
    {
        return config('service');
    }

    /**
     * Satu layanan berdasarkan slug; null bila tidak ada.
     *
     * @return array<string, mixed>|null
     */
    public function findBySlug(string $slug): ?array
    {
        return collect(config('service.items'))->firstWhere('slug', $slug);
    }

    /**
     * @return array<string, string>
     */
    public function detailCta(): array
    {
        return config('service.detail_cta');
    }
}
