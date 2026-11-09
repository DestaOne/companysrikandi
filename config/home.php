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

    'services' => [
        'eyebrow' => 'Layanan Kami',
        'title' => 'Bidang Usaha yang Kami Layani',
        'items' => [
            [
                'title' => 'Mesin dan Peralatan',
                'summary' => 'Perdagangan besar mesin, peralatan, dan perlengkapan penunjang usaha.',
                'image' => 'images/home/layanan-mesin.jpg',
            ],
            [
                'title' => 'Karet dan Plastik',
                'summary' => 'Karet dan plastik dalam bentuk dasar untuk kebutuhan produksi.',
                'image' => 'images/home/layanan-karet-plastik.jpg',
            ],
            [
                'title' => 'Komputer dan Perlengkapan',
                'summary' => 'Komputer beserta perlengkapannya untuk kebutuhan kantor dan usaha.',
                'image' => 'images/home/layanan-komputer.jpg',
            ],
            [
                'title' => 'Alat Laboratorium dan Kedokteran',
                'summary' => 'Alat laboratorium, farmasi, dan kedokteran untuk manusia.',
                'image' => 'images/home/layanan-laboratorium.jpg',
            ],
        ],
    ],

    // Tiga keunggulan: kartu pertama tampil lebar di atas, dua lainnya berdampingan di bawahnya.
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
        ],
    ],

    'cta' => [
        'title' => 'Butuh pasokan untuk usaha Anda?',
        'description' => 'Hubungi kami untuk menanyakan ketersediaan barang dan penawaran harga.',
        'label' => 'Hubungi Kami',
        'image' => 'images/home/cta.jpg',
    ],

];
