<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Isi awal daftar layanan dari config/service.php; aman dijalankan ulang.
     */
    public function run(): void
    {
        foreach (config('service.items') as $index => $item) {
            $service = Service::firstOrNew(['slug' => $item['slug']])->fill($item);

            // Urutan hanya diisi untuk layanan baru, supaya urutan atur admin tidak terhapus saat seeder diulang
            if (! $service->exists) {
                $service->sort_order = $index + 1;
            }

            $service->save();
        }
    }
}
