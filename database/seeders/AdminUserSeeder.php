<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Akun admin awal. Tanpa ADMIN_PASSWORD di .env, seeder tidak membuat akun
     * supaya tidak ada kata sandi bawaan yang tertinggal di server.
     */
    public function run(): void
    {
        $password = config('company.admin.password');

        if (! $password) {
            $this->command?->warn('ADMIN_PASSWORD belum diisi di .env, akun admin tidak dibuat.');

            return;
        }

        User::updateOrCreate(
            ['email' => config('company.admin.email')],
            ['name' => 'Admin', 'password' => $password],
        );
    }
}
