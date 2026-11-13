<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ServiceCatalogService;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function __construct(private ServiceCatalogService $serviceCatalogService) {}

    public function index(): View
    {
        return view('web.services', ['page' => $this->serviceCatalogService->page()]);
    }

    public function show(string $slug): View
    {
        $service = $this->serviceCatalogService->findBySlug($slug) ?? abort(404);

        return view('web.service-detail', [
            'service' => $service,
            'cta' => $this->serviceCatalogService->detailCta(),
        ]);
    }
}
