<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('web.home', [
            'hero' => config('home.hero'),
            'services' => config('home.services'),
            'features' => config('home.features'),
            'cta' => config('home.cta'),
        ]);
    }
}
