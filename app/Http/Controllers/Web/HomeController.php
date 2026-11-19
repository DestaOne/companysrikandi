<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\HomeContentService;
use App\Services\ServiceCatalogService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private HomeContentService $homeContentService,
        private ServiceCatalogService $serviceCatalogService,
    ) {}

    public function __invoke(): View
    {
        $content = $this->homeContentService->all();

        return view('web.home', [
            'hero' => $content['hero'],
            'services' => [...$content['services'], 'items' => $this->serviceCatalogService->homeCards($content['services']['service_ids'])],
            'features' => $content['features'],
            'cta' => $content['cta'],
        ]);
    }
}
