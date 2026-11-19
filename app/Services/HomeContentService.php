<?php

namespace App\Services;

use App\Enums\HomeSectionKey;
use App\Helpers\UploadHelper;
use App\Models\HomeSection;
use Illuminate\Http\UploadedFile;
use Throwable;

class HomeContentService
{
    /**
     * Isi semua section: bawaan dari config, ditimpa data dari database.
     *
     * @return array<string, array<string, mixed>>
     */
    public function all(): array
    {
        $stored = HomeSection::all()->pluck('content', 'section');
        $content = [];

        foreach (HomeSectionKey::cases() as $key) {
            // Tiap bagian menimpa utuh: daftar yang lebih pendek tidak boleh bercampur dengan daftar bawaan
            $content[$key->value] = array_replace(
                $this->defaults($key),
                $stored[$key->value] ?? [],
            );
        }

        return $content;
    }

    /**
     * Isi bawaan sebuah section. Kontak mengambil alamat, telepon, dan email dari profil perusahaan.
     *
     * @return array<string, mixed>
     */
    private function defaults(HomeSectionKey $key): array
    {
        $defaults = config($key->configPath());

        if ($key === HomeSectionKey::AboutContact) {
            $defaults += [
                'address' => config('company.address'),
                'phone' => config('company.phone'),
                'email' => config('company.email'),
            ];
        }

        return $defaults;
    }

    /**
     * @return array<string, mixed>
     */
    public function section(HomeSectionKey $key): array
    {
        return $this->all()[$key->value];
    }

    /**
     * Ubah teks dan gambar utama sebuah section.
     *
     * @param  array<string, mixed>  $data  teks hasil validasi
     */
    public function update(HomeSectionKey $key, array $data, ?UploadedFile $image = null): HomeSection
    {
        [$content, $newPath, $oldUrl] = $this->withImage([...$this->section($key), ...$this->toLists($key, $data)], $image);

        return $this->save($key, $content, [$newPath], [$oldUrl]);
    }

    /**
     * Ganti gambar-gambar logo; hanya gambar yang diunggah yang berubah.
     *
     * @param  array<string, UploadedFile>  $files  nama kolom => file baru
     */
    public function updateImages(HomeSectionKey $key, array $files): HomeSection
    {
        $content = $this->section($key);
        $newPaths = [];
        $oldUrls = [];

        foreach ($files as $name => $file) {
            $path = UploadHelper::store($file, 'home');
            $newPaths[] = $path;
            $oldUrls[] = $content[$name] ?? null;
            $content[$name] = 'storage/'.$path;
        }

        return $this->save($key, $content, $newPaths, $oldUrls);
    }

    /** Kembalikan satu gambar ke isi bawaan config; file unggahan lamanya dihapus. */
    public function resetImage(HomeSectionKey $key, string $name): HomeSection
    {
        $content = $this->section($key);
        $oldUrl = $content[$name] ?? null;
        $content[$name] = $this->defaults($key)[$name];

        return $this->save($key, $content, [], [$oldUrl]);
    }

    /**
     * Ubah satu kartu pada section yang punya kartu (mis. Keunggulan).
     *
     * @param  array<string, mixed>  $data  teks hasil validasi
     */
    public function updateItem(HomeSectionKey $key, int $index, array $data, ?UploadedFile $image = null): HomeSection
    {
        $content = $this->section($key);
        [$item, $newPath, $oldUrl] = $this->withImage([...$content['items'][$index], ...$data], $image);
        $content['items'][$index] = $item;

        return $this->save($key, $content, [$newPath], [$oldUrl]);
    }

    /**
     * Ubah kolom bertipe "lines" (satu baris = satu butir) menjadi daftar.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function toLists(HomeSectionKey $key, array $data): array
    {
        foreach ($key->fields() as $name => $field) {
            if ($field['type'] === 'lines' && isset($data[$name])) {
                $data[$name] = array_values(array_filter(array_map('trim', preg_split('/\R/', $data[$name]))));
            }
        }

        return $data;
    }

    /**
     * Unggah gambar baru (bila ada) dan pasang pathnya ke bagian isi.
     *
     * @param  array<string, mixed>  $target
     * @return array{0: array<string, mixed>, 1: ?string, 2: ?string} bagian isi, path file baru, path gambar lama
     */
    private function withImage(array $target, ?UploadedFile $file): array
    {
        if (! $file) {
            return [$target, null, null];
        }

        $path = UploadHelper::store($file, 'home');
        $oldUrl = $target['image'] ?? null;
        $target['image'] = 'storage/'.$path;

        return [$target, $path, $oldUrl];
    }

    /**
     * @param  array<string, mixed>  $content
     * @param  array<int, ?string>  $newPaths  file yang baru diunggah (dibersihkan bila gagal simpan)
     * @param  array<int, ?string>  $oldUrls  gambar lama (dihapus setelah berhasil simpan)
     */
    private function save(HomeSectionKey $key, array $content, array $newPaths, array $oldUrls): HomeSection
    {
        try {
            $section = HomeSection::updateOrCreate(['section' => $key->value], ['content' => $content]);
        } catch (Throwable $e) {
            array_map(UploadHelper::delete(...), $newPaths); // bersihkan file yatim, lalu lempar ulang
            throw $e;
        }

        array_map(UploadHelper::deleteUrl(...), $oldUrls); // gambar bawaan (bukan "storage/") tidak disentuh

        return $section;
    }
}
