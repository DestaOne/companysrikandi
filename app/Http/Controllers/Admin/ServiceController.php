<?php

namespace App\Http\Controllers\Admin;

use App\Enums\HomeSectionKey;
use App\Helpers\MessageHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReorderServiceRequest;
use App\Http\Requests\Admin\SearchServiceRequest;
use App\Http\Requests\Admin\StoreServiceRequest;
use App\Http\Requests\Admin\UpdateServiceRequest;
use App\Models\Service;
use App\Services\HomeContentService;
use App\Services\ServiceCatalogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Throwable;

class ServiceController extends Controller
{
    public function __construct(
        private ServiceCatalogService $serviceCatalogService,
        private HomeContentService $homeContentService,
    ) {}

    public function index(SearchServiceRequest $request): View
    {
        $content = $this->homeContentService->all();

        return view('admin.services.index', [
            'services' => $this->serviceCatalogService->search($request->validated('q')),
            'search' => (string) $request->validated('q'),
            'banner' => $content[HomeSectionKey::ServiceBanner->value],
            'cta' => $content[HomeSectionKey::ServiceCta->value],
        ]);
    }

    public function create(): View
    {
        return view('admin.services.create');
    }

    public function store(StoreServiceRequest $request): RedirectResponse
    {
        try {
            $this->serviceCatalogService->store($request->safe()->except('image'), $request->file('image'));
        } catch (Throwable $e) {
            report($e);

            return back()->withInput()->with(MessageHelper::error('Gagal menyimpan layanan.'));
        }

        return redirect()->route('admin.services.index')
            ->with(MessageHelper::success('Layanan berhasil ditambahkan.'));
    }

    public function edit(Service $service): View
    {
        return view('admin.services.edit', ['service' => $service]);
    }

    public function update(UpdateServiceRequest $request, Service $service): RedirectResponse
    {
        try {
            $this->serviceCatalogService->update($service, $request->safe()->except('image'), $request->file('image'));
        } catch (Throwable $e) {
            report($e);

            return back()->withInput()->with(MessageHelper::error('Gagal memperbarui layanan.'));
        }

        return redirect()->route('admin.services.index')
            ->with(MessageHelper::success('Layanan berhasil diperbarui.'));
    }

    public function reorder(ReorderServiceRequest $request): RedirectResponse|Response
    {
        try {
            $this->serviceCatalogService->reorder($request->validated('ids'));
        } catch (Throwable $e) {
            report($e);

            return back()->with(MessageHelper::error('Gagal menyimpan urutan layanan.'));
        }

        // Dikirim ke iframe tersembunyi (lihat sortableRows.js): sukses cukup 204 tanpa isi, halaman admin tidak berpindah
        return response()->noContent();
    }

    public function destroy(Service $service): RedirectResponse
    {
        try {
            $this->serviceCatalogService->delete($service);
        } catch (Throwable $e) {
            report($e);

            return back()->with(MessageHelper::error('Gagal menghapus layanan.'));
        }

        return redirect()->route('admin.services.index')
            ->with(MessageHelper::success('Layanan berhasil dihapus.'));
    }
}
