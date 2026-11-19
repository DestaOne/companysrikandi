<?php

namespace App\Services;

use App\Enums\HomeSectionKey;
use App\Helpers\UploadHelper;
use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Throwable;

class ServiceCatalogService
{
    public function __construct(
        private HomeContentService $homeContentService,
        private SortService $sortService,
    ) {}

    /**
     * Isi halaman Layanan: banner dan CTA yang bisa diedit, intro dari config, daftar dari database.
     *
     * @return array<string, mixed>
     */
    public function page(): array
    {
        $content = $this->homeContentService->all();

        return [
            'banner' => $content[HomeSectionKey::ServiceBanner->value],
            'intro' => config('service.intro'),
            'items' => $this->all(),
            'cta' => $content[HomeSectionKey::ServiceCta->value],
        ];
    }

    /**
     * Layanan terbit untuk halaman publik (jumlahnya kecil, satu halaman).
     *
     * @return Collection<int, Service>
     */
    public function all(): Collection
    {
        return Service::published()->ordered()->get();
    }

    /**
     * Pilihan layanan terbit untuk kartu Home di admin: id => judul dan URL gambar.
     *
     * @return array<int, array{title: string, image: string}>
     */
    public function pickerItems(): array
    {
        return $this->all()
            ->mapWithKeys(fn (Service $service) => [$service->id => ['title' => $service->title, 'image' => asset($service->image)]])
            ->all();
    }

    /**
     * Kartu layanan untuk carousel Home: layanan terbit pilihan admin (maksimal 4).
     * Bila belum ada pilihan yang valid, empat layanan terbit pertama.
     *
     * @param  array<int, int|string>  $ids
     * @return array<int, array{id: int, title: string, summary: string, image: string}>
     */
    public function homeCards(array $ids): array
    {
        $published = $this->all()->keyBy('id');
        $chosen = collect($ids)->map(fn ($id) => $published->get((int) $id))->filter();

        return ($chosen->isEmpty() ? $published->take(4) : $chosen)
            ->map(fn (Service $service): array => [
                'id' => $service->id,
                'title' => $service->title,
                'summary' => Str::limit($service->description, 110),
                'image' => $service->image,
            ])
            ->values()
            ->all();
    }

    /**
     * Layanan terbit untuk daftar tautan di footer dan menu navbar, sesuai urutan di admin.
     *
     * @return Collection<int, Service>
     */
    public function publishedLinks(int $limit = 15): Collection
    {
        return Service::published()->ordered()->limit($limit)->get(['id', 'title', 'slug']);
    }

    /**
     * Layanan yang masih draft (belum tampil di website), yang terakhir diubah di atas.
     *
     * @return Collection<int, Service>
     */
    public function drafts(int $limit = 30): Collection
    {
        return Service::draft()->latest('updated_at')->latest('id')->limit($limit)->get();
    }

    /** Jumlah layanan draft. */
    public function draftCount(): int
    {
        return Service::draft()->count();
    }

    /**
     * Daftar layanan untuk admin, tanpa halaman: tabel menggulir sendiri di dalamnya.
     * $search mencari di judul, deskripsi, dan alamat halaman.
     *
     * @return Collection<int, Service>
     */
    public function search(?string $search = null): Collection
    {
        $term = trim((string) $search);

        return Service::ordered()
            ->when($term !== '', fn (Builder $query) => $query->where(
                fn (Builder $match) => $match->where('title', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhere('slug', 'like', "%{$term}%"),
            ))
            ->get();
    }

    public function count(): int
    {
        return Service::count();
    }

    /** Hanya layanan terbit; draft diperlakukan seperti tidak ada. */
    public function findPublishedBySlug(string $slug): ?Service
    {
        return Service::published()->where('slug', $slug)->first();
    }

    /**
     * @return array<string, string>
     */
    public function detailCta(): array
    {
        return config('service.detail_cta');
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function store(array $data, UploadedFile $image): Service
    {
        $path = UploadHelper::store($image, 'services');

        try {
            return Service::create([
                ...$this->prepare($data),
                'slug' => $this->uniqueSlug($data['title']),
                'image' => 'storage/'.$path,
                'sort_order' => (Service::max('sort_order') ?? 0) + 1, // layanan baru di urutan terakhir
            ]);
        } catch (Throwable $e) {
            UploadHelper::delete($path); // bersihkan file yatim, lalu lempar ulang
            throw $e;
        }
    }

    /**
     * Slug tidak berubah saat judul diedit agar tautan lama tetap berlaku.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(Service $service, array $data, ?UploadedFile $image = null): Service
    {
        $oldImage = $service->image;
        $newPath = $image ? UploadHelper::store($image, 'services') : null;

        try {
            $service->update($newPath ? [...$this->prepare($data), 'image' => 'storage/'.$newPath] : $this->prepare($data));
        } catch (Throwable $e) {
            UploadHelper::delete($newPath);
            throw $e;
        }

        if ($newPath) {
            UploadHelper::deleteUrl($oldImage); // hapus file lama hanya setelah DB berhasil
        }

        return $service;
    }

    /**
     * Susun ulang layanan sesuai urutan id. Nilai urutan yang sudah dipakai layanan-layanan itu
     * dibagikan ulang, jadi yang diseret hanya bertukar tempat di antara mereka (aman untuk
     * tabel yang dipecah per halaman).
     *
     * @param  array<int, int|string>  $ids
     */
    public function reorder(array $ids): void
    {
        $this->sortService->reorder(Service::class, $ids);
    }

    public function delete(Service $service): void
    {
        $service->delete();
        UploadHelper::deleteUrl($service->image);
    }

    /**
     * Daftar barang diketik satu baris per barang; simpan sebagai array.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function prepare(array $data): array
    {
        $lines = preg_split('/\R/', $data['products']) ?: [];

        return [
            ...$data,
            'products' => array_values(array_filter(array_map('trim', $lines), fn (string $line): bool => $line !== '')),
        ];
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $number = 2;

        while (Service::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$number++;
        }

        return $slug;
    }
}
