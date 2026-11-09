<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Profil Perusahaan
    |--------------------------------------------------------------------------
    |
    | Data yang tampil di footer. Setiap nilai bisa ditimpa lewat .env
    | (COMPANY_NAME, COMPANY_PHONE, dst.). Daftar layanan di bawah adalah
    | bidang usaha (KBLI) dan nanti diganti dengan data dari database.
    |
    */

    'name' => env('COMPANY_NAME', 'PT. Dwi Srikandi Nusantara'),

    'description' => env('COMPANY_DESCRIPTION', 'Perusahaan perdagangan besar di Kosambi, Kabupaten Tangerang, yang memasok mesin dan peralatan, bahan karet dan plastik, perlengkapan rumah tangga, komputer, alat laboratorium dan kedokteran, serta pakaian.'),

    'address' => env('COMPANY_ADDRESS', 'Jalan Scarlet 3 Nomor 9, Kelurahan Selembaran Jati, Kecamatan Kosambi, Kabupaten Tangerang, Provinsi Banten'),

    'phone' => env('COMPANY_PHONE', '081133361090'),

    'email' => env('COMPANY_EMAIL', 'dwisrikandinusantara@gmail.com'),

    // Akun admin awal untuk seeder. Isi ADMIN_PASSWORD di .env sebelum db:seed.
    'admin' => [
        'email' => env('ADMIN_EMAIL', 'admin@dwisrikandinusantara.test'),
        'password' => env('ADMIN_PASSWORD'),
    ],

    // Kosongkan (null) agar baris jam operasional tidak ditampilkan.
    'hours' => env('COMPANY_HOURS'),

    // Placeholder daftar layanan di footer; nanti diganti data dari database.
    'footer_services' => [
        'Layanan 1',
        'Layanan 2',
        'Layanan 3',
        'Layanan 4',
    ],

    'services' => [
        'Perdagangan Besar Mesin, Peralatan dan Perlengkapan Lainnya',
        'Perdagangan Besar Karet dan Plastik dalam Bentuk Dasar',
        'Perdagangan Besar Peralatan Masak, Peralatan Dapur, dan Elektronik Rumah Tangga',
        'Perdagangan Besar Komputer dan Perlengkapan Komputer',
        'Perdagangan Besar Berbagai Macam Barang',
        'Perdagangan Besar Alat Laboratorium, Alat Farmasi, dan Alat Kedokteran untuk Manusia',
        'Perdagangan Besar Pakaian',
    ],

];
