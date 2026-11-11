<?php

namespace Database\Seeders;

use App\Models\Gallery;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class GallerySeeder extends Seeder
{
    /**
     * Foto placeholder dari Unsplash (lisensi bebas pakai). Ganti lewat panel admin.
     * Urutan menentukan ukuran petak pada grid galeri.
     *
     * @var array<string, array{0: string, 1: string}> berkas => [judul, deskripsi]
     */
    private const PHOTOS = [
        'gudang-utama' => ['Gudang Utama', 'Penyimpanan barang dalam jumlah besar yang tertata rapi.'],
        'pelabuhan' => ['Pengiriman Antarpulau', 'Distribusi barang melalui pelabuhan peti kemas.'],
        'alat-laboratorium' => ['Alat Laboratorium', 'Perlengkapan laboratorium dan farmasi.'],
        'lorong-gudang' => ['Lorong Penyimpanan', 'Rak bertingkat untuk stok yang selalu tersedia.'],
        'mesin-produksi' => ['Mesin dan Peralatan', 'Mesin dan peralatan penunjang usaha.'],
        'armada-pengiriman' => ['Armada Pengiriman', 'Armada truk untuk pengiriman tepat waktu.'],
        'perangkat-komputer' => ['Komputer dan Perlengkapan', 'Komponen dan perlengkapan komputer.'],
        'rapat-pelanggan' => ['Pertemuan dengan Pelanggan', 'Diskusi kebutuhan dan penawaran bersama mitra.'],
        'bengkel-peralatan' => ['Perawatan Peralatan', 'Pengerjaan dan pemeriksaan peralatan.'],
        'perlengkapan-kantor' => ['Perlengkapan Kantor', 'Perangkat kerja untuk kebutuhan kantor dan usaha.'],
        'stok-barang' => ['Stok Barang', 'Persediaan barang dalam partai besar.'],
        'tim-kerja' => ['Tim Kami', 'Tim yang siap melayani kebutuhan pelanggan.'],
        'presentasi-produk' => ['Presentasi Produk', 'Pengenalan produk kepada calon mitra.'],
        'kemitraan' => ['Kemitraan', 'Kerja sama jangka panjang yang saling menguntungkan.'],
    ];

    public function run(): void
    {
        if (Gallery::exists()) {
            $this->command?->warn('Galeri sudah berisi data, seeder dilewati.');

            return;
        }

        foreach (self::PHOTOS as $file => [$title, $description]) {
            $path = "galleries/{$file}.jpg";
            Storage::disk('public')->put($path, file_get_contents(__DIR__."/images/galleries/{$file}.jpg"));

            Gallery::create(['title' => $title, 'description' => $description, 'image' => $path]);
        }
    }
}
