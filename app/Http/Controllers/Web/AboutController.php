<?php

namespace App\Http\Controllers\Web;

use App\Enums\HomeSectionKey;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\StoreContactMessageRequest;
use App\Services\ContactMessageService;
use App\Services\HomeContentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Throwable;

class AboutController extends Controller
{
    public function __construct(
        private ContactMessageService $contactMessageService,
        private HomeContentService $homeContentService,
    ) {}

    public function index(): View
    {
        $content = $this->homeContentService->all();

        return view('web.about', [
            'banner' => $content[HomeSectionKey::AboutBanner->value],
            'background' => $content[HomeSectionKey::AboutBackground->value],
            'vision' => $content[HomeSectionKey::AboutVision->value],
            'mission' => $content[HomeSectionKey::AboutMission->value],
            'contact' => $content[HomeSectionKey::AboutContact->value],
        ]);
    }

    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        try {
            $message = $this->contactMessageService->store($request->validated());
            $mailDraft = $this->contactMessageService->mailDraft($message, $this->contactEmail());
        } catch (Throwable $e) {
            report($e);

            return redirect(route('about').'#kontak')->withInput()->with('error', 'Gagal mengirim pesan. Silakan coba lagi.');
        }

        return redirect(route('about').'#kontak')->with('mail_draft', $mailDraft);
    }

    /** Email tujuan sama dengan yang tampil di bagian Kontak (diatur admin). */
    private function contactEmail(): string
    {
        return $this->homeContentService->section(HomeSectionKey::AboutContact)['email'] ?? config('company.email');
    }
}
