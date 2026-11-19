<?php

namespace App\Services;

use App\Models\ContactMessage;
use App\Models\Gallery;
use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;

class DashboardService
{
    public function __construct(private ServiceCatalogService $serviceCatalogService) {}

    /**
     * @return array{services: int, draft_services: int, galleries: int, unread_messages: int}
     */
    public function counts(): array
    {
        return [
            'services' => $this->serviceCatalogService->count(),
            'draft_services' => $this->serviceCatalogService->draftCount(),
            'galleries' => Gallery::count(),
            'unread_messages' => ContactMessage::unread()->count(),
        ];
    }

    /**
     * @return Collection<int, ContactMessage>
     */
    public function recentMessages(int $limit = 30): Collection
    {
        return ContactMessage::latest()->limit($limit)->get();
    }

    /**
     * @return Collection<int, Service>
     */
    public function draftServices(int $limit = 30): Collection
    {
        return $this->serviceCatalogService->drafts($limit);
    }
}
