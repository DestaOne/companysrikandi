<?php

namespace App\Http\Controllers\Admin;

use App\Enums\HomeSectionKey;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateHomeSectionItemRequest;
use App\Http\Requests\Admin\UpdateHomeSectionRequest;
use App\Services\HomeContentService;
use App\Services\ServiceCatalogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class HomeSectionController extends Controller
{
    public function __construct(
        private HomeContentService $homeContentService,
        private ServiceCatalogService $serviceCatalogService,
    ) {}

    public function index(): View
    {
        $content = $this->homeContentService->all();
        $content['services']['items'] = $this->serviceCatalogService->homeCards($content['services']['service_ids']);

        return view('admin.home-sections.index', ['content' => $content]);
    }

    public function edit(Request $request, HomeSectionKey $homeSection): View
    {
        $content = $this->homeContentService->section($homeSection);

        // Section berkartu (Keunggulan) menampilkan daftar kartu dulu; form judul bagian dibuka lewat ?judul=1
        if ($homeSection->hasItems() && ! $request->boolean('judul')) {
            return view('admin.home-sections.cards', ['section' => $homeSection, 'content' => $content]);
        }

        $picksServices = $homeSection->picksServices();

        return view('admin.home-sections.edit', [
            'section' => $homeSection,
            'content' => $content,
            'pickerItems' => $picksServices ? $this->serviceCatalogService->pickerItems() : [],
            'selectedIds' => $picksServices
                ? array_column($this->serviceCatalogService->homeCards($content['service_ids']), 'id')
                : [],
        ]);
    }

    public function update(UpdateHomeSectionRequest $request, HomeSectionKey $homeSection): RedirectResponse
    {
        try {
            $this->homeContentService->update($homeSection, $request->safe()->except('image'), $request->file('image'));
        } catch (Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Gagal menyimpan perubahan.');
        }

        // Section berkartu kembali ke daftar kartunya
        $target = $homeSection->hasItems()
            ? route('admin.home-sections.edit', $homeSection)
            : route($homeSection->indexRoute());

        return redirect($target)->with('success', $homeSection->label().' berhasil diperbarui.');
    }

    public function editItem(HomeSectionKey $homeSection, int $index): View
    {
        $content = $this->homeContentService->section($homeSection);

        return view('admin.home-sections.edit-item', [
            'section' => $homeSection,
            'index' => $index,
            'item' => $content['items'][$index] ?? abort(404),
        ]);
    }

    public function updateItem(UpdateHomeSectionItemRequest $request, HomeSectionKey $homeSection, int $index): RedirectResponse
    {
        abort_unless(isset($this->homeContentService->section($homeSection)['items'][$index]), 404);

        try {
            $this->homeContentService->updateItem($homeSection, $index, $request->safe()->except('image'), $request->file('image'));
        } catch (Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Gagal menyimpan perubahan.');
        }

        return redirect()->route('admin.home-sections.edit', $homeSection)
            ->with('success', 'Kartu '.($index + 1).' berhasil diperbarui.');
    }
}
