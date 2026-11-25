<?php

namespace App\Http\Controllers\Admin;

use App\Enums\HomeSectionKey;
use App\Helpers\MessageHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateBrandingRequest;
use App\Services\HomeContentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Throwable;

class BrandingController extends Controller
{
    public function __construct(private HomeContentService $homeContentService) {}

    public function edit(): View
    {
        return view('admin.branding.edit', [
            'fields' => HomeSectionKey::Branding->logoFields(),
            'content' => $this->homeContentService->section(HomeSectionKey::Branding),
            'defaults' => config(HomeSectionKey::Branding->configPath()),
        ]);
    }

    public function update(UpdateBrandingRequest $request): RedirectResponse
    {
        try {
            // Kolom yang tidak diunggah bernilai null dan dibuang, jadi logo lamanya tetap
            $this->homeContentService->updateImages(HomeSectionKey::Branding, array_filter($request->validated()));
        } catch (Throwable $e) {
            report($e);

            return back()->with(MessageHelper::error('Gagal menyimpan logo.'));
        }

        return redirect()->route('admin.branding.edit')->with(MessageHelper::success('Logo berhasil diperbarui.'));
    }

    public function reset(string $field): RedirectResponse
    {
        $fields = HomeSectionKey::Branding->logoFields();
        abort_unless(isset($fields[$field]), 404);

        try {
            $this->homeContentService->resetImage(HomeSectionKey::Branding, $field);
        } catch (Throwable $e) {
            report($e);

            return back()->with(MessageHelper::error('Gagal mengembalikan logo.'));
        }

        return redirect()->route('admin.branding.edit')->with(MessageHelper::success($fields[$field]['label'].' dikembalikan ke bawaan.'));
    }
}
