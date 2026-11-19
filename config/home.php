<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Isi Halaman Beranda
    |--------------------------------------------------------------------------
    |
    | Teks dan gambar tiap bagian beranda. Gambar berada di public/images/home
    | (foto Unsplash, lisensi bebas pakai). Nanti diganti data dari database
    | agar bisa diedit lewat panel admin.
    |
    */

    'hero' => [
        'title' => 'Pemasok Perdagangan Besar untuk Kebutuhan Usaha Anda',
        'description' => 'Mesin dan peralatan, karet dan plastik, perlengkapan rumah tangga, komputer, hingga alat laboratorium, tersedia dari satu pemasok.',
        'image' => 'images/home/hero.jpg',
    ],

    // Empat kartu layanan dipilih admin dari daftar Layanan (menyimpan id layanan).
    // Bila belum dipilih, empat layanan terbit pertama yang dipakai.
    'services' => [
        'eyebrow' => 'Layanan Kami',
        'title' => 'Bidang Usaha yang Kami Layani',
        'service_ids' => [],
    ],

    // Empat keunggulan: panel foto yang melebar saat di-hover; panel pertama terbuka lebih dulu.
    'features' => [
        'eyebrow' => 'Keunggulan',
        'title' => 'Mengapa Memilih Kami',
        'items' => [
            [
                'title' => 'Pasokan Partai Besar',
                'description' => 'Kami melayani pembelian dalam jumlah besar untuk kebutuhan usaha dan instansi.',
                'image' => 'images/home/keunggulan-partai-besar.jpg',
            ],
            [
                'title' => 'Pilihan Produk Lengkap',
                'description' => 'Dari mesin hingga pakaian, kebutuhan Anda tersedia dari satu pemasok.',
                'image' => 'images/home/keunggulan-produk-lengkap.jpg',
            ],
            [
                'title' => 'Mitra yang Responsif',
                'description' => 'Tim kami mudah dihubungi dan siap membantu kebutuhan pemesanan Anda.',
                'image' => 'images/home/keunggulan-mitra.jpg',
            ],
            [
                'title' => 'Pengiriman Tepat Waktu',
                'description' => 'Armada dan jaringan distribusi kami memastikan barang sampai sesuai jadwal yang disepakati.',
                'image' => 'images/home/keunggulan-pengiriman.jpg',
            ],
        ],
    ],

    'cta' => [
        'title' => 'Butuh pasokan untuk usaha Anda?',
        'description' => 'Hubungi kami untuk menanyakan ketersediaan barang dan penawaran harga.',
        'label' => 'Hubungi Kami',
        'image' => 'images/home/cta.jpg',
    ],

];
