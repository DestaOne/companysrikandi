<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\MessageHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReorderGalleryRequest;
use App\Http\Requests\Admin\StoreGalleryRequest;
use App\Http\Requests\Admin\UpdateGalleryRequest;
use App\Models\Gallery;
use App\Services\GalleryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Throwable;

class GalleryController extends Controller
{
    public function __construct(private GalleryService $galleryService) {}

    public function index(): View
    {
        return view('admin.galleries.index', [
            'galleries' => $this->galleryService->all(),
        ]);
    }

    public function create(): View
    {
        return view('admin.galleries.create');
    }

    public function store(StoreGalleryRequest $request): RedirectResponse
    {
        try {
            $this->galleryService->store($request->safe()->except('image'), $request->file('image'));
        } catch (Throwable $e) {
            report($e);

            return back()->withInput()->with(MessageHelper::error('Gagal menyimpan galeri.'));
        }

        return redirect()->route('admin.galleries.index')
            ->with(MessageHelper::success('Galeri berhasil ditambahkan.'));
    }

    public function edit(Gallery $gallery): View
    {
        return view('admin.galleries.edit', ['gallery' => $gallery]);
    }

    public function update(UpdateGalleryRequest $request, Gallery $gallery): RedirectResponse
    {
        try {
            $this->galleryService->update($gallery, $request->safe()->except('image'), $request->file('image'));
        } catch (Throwable $e) {
            report($e);

            return back()->withInput()->with(MessageHelper::error('Gagal memperbarui galeri.'));
        }

        return redirect()->route('admin.galleries.index')
            ->with(MessageHelper::success('Galeri berhasil diperbarui.'));
    }

    public function reorder(ReorderGalleryRequest $request): RedirectResponse|Response
    {
        try {
            $this->galleryService->reorder($request->validated('ids'));
        } catch (Throwable $e) {
            report($e);

            return back()->with(MessageHelper::error('Gagal menyimpan urutan galeri.'));
        }

        // Dikirim ke iframe tersembunyi (lihat sortableRows.js): sukses cukup 204 tanpa isi, halaman admin tidak berpindah
        return response()->noContent();
    }

    public function destroy(Gallery $gallery): RedirectResponse
    {
        try {
            $this->galleryService->delete($gallery);
        } catch (Throwable $e) {
            report($e);

            return back()->with(MessageHelper::error('Gagal menghapus galeri.'));
        }

        return redirect()->route('admin.galleries.index')
            ->with(MessageHelper::success('Galeri berhasil dihapus.'));
    }
}
