<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\ContactMessage;
use App\Models\Gallery;
use Illuminate\Database\Eloquent\Collection;

class DashboardService
{
    public function __construct(private ServiceCatalogService $serviceCatalogService) {}

    /**
     * @return array{services: int, galleries: int, unread_messages: int}
     */
    public function counts(): array
    {
        return [
            // Layanan masih dari config sampai ada modul admin-nya
            'services' => count($this->serviceCatalogService->page()['items']),
            'galleries' => Gallery::count(),
            'unread_messages' => ContactMessage::unread()->count(),
        ];
    }

    /**
     * @return Collection<int, ContactMessage>
     */
    public function recentMessages(int $limit = 5): Collection
    {
        return ContactMessage::latest()->limit($limit)->get();
    }

    /**
     * @return Collection<int, ActivityLog>
     */
    public function recentActivities(int $limit = 6): Collection
    {
        return ActivityLog::with('user')->latest()->limit($limit)->get();
    }
}
