<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\HomeContentService;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function __construct(private HomeContentService $homeContentService) {}

    public function __invoke(): View
    {
        return view('admin.about.index', ['content' => $this->homeContentService->all()]);
    }
}
