<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Profil Perusahaan
    |--------------------------------------------------------------------------
    |
    | Data yang tampil di footer. Setiap nilai bisa ditimpa lewat .env
    | (COMPANY_NAME, COMPANY_PHONE, dst.). Daftar layanan tidak diatur di sini,
    | melainkan dari menu Layanan di panel admin.
    |
    */

    'name' => env('COMPANY_NAME', 'PT. Dwi Srikandi Nusantara'),

    'description' => env('COMPANY_DESCRIPTION', 'Perusahaan perdagangan besar di Kosambi, Kabupaten Tangerang, yang memasok mesin dan peralatan, bahan karet dan plastik, perlengkapan rumah tangga, komputer, alat laboratorium dan kedokteran, serta pakaian.'),

    'address' => env('COMPANY_ADDRESS', 'Jalan Scarlet 3 Nomor 9, Kelurahan Selembaran Jati, Kecamatan Kosambi, Kabupaten Tangerang, Provinsi Banten'),

    'phone' => env('COMPANY_PHONE', '081133361090'),

    // Zona waktu untuk menampilkan tanggal dan jam di panel admin (database tetap menyimpan UTC).
    'timezone' => env('COMPANY_TIMEZONE', 'Asia/Jakarta'),

    'email' => env('COMPANY_EMAIL', 'dwisrikandinusantara@gmail.com'),

    // Akun admin awal untuk seeder. Isi ADMIN_PASSWORD di .env sebelum db:seed.
    'admin' => [
        'email' => env('ADMIN_EMAIL', 'admin@dwisrikandinusantara.test'),
        'password' => env('ADMIN_PASSWORD'),
    ],

    // Kosongkan (null) agar baris jam operasional tidak ditampilkan.
    'hours' => env('COMPANY_HOURS'),

    // Logo bawaan (path di folder public); admin bisa menggantinya lewat menu Logo.
    'logos' => [
        'favicon' => 'images/logo.png',
        'navbar_logo_light' => 'images/logo-light.png',
        'navbar_logo' => 'images/logo.png',
        'footer_logo' => 'images/logo.png',
    ],

];
