<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\StoreContactMessageRequest;
use App\Services\ContactMessageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Throwable;

class AboutController extends Controller
{
    public function __construct(private ContactMessageService $contactMessageService) {}

    public function index(): View
    {
        return view('web.about', ['about' => config('about')]);
    }

    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        try {
            $this->contactMessageService->store($request->validated());
        } catch (Throwable $e) {
            report($e);

            return redirect(route('about').'#kontak')->withInput()->with('error', 'Gagal mengirim pesan. Silakan coba lagi.');
        }

        return redirect(route('about').'#kontak')->with('success', 'Pesan Anda terkirim. Kami akan segera menghubungi Anda.');
    }
}
