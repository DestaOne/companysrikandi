<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function __invoke(): View
    {
        return view('web.services', ['page' => config('service')]);
    }
}
