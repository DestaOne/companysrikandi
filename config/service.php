<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Isi Halaman Layanan
    |--------------------------------------------------------------------------
    |
    | Daftar layanan ditampilkan bergantian (gambar kiri, lalu kanan, dst.).
    | Nanti dapat diganti data dari database.
    |
    */

    'banner' => [
        'title' => 'Layanan PT. Dwi Srikandi Nusantara',
        'description' => 'Tujuh bidang usaha yang kami layani, dari mesin dan peralatan hingga alat laboratorium, kedokteran, dan pakaian, semuanya dari satu pemasok.',
        'image' => 'images/home/hero-layanan.jpg',
    ],

    'intro' => [
        'eyebrow' => 'Bidang Usaha',
        'title' => 'Kebutuhan Usaha Anda, Satu Pintu',
    ],

    'items' => [
        [
            'slug' => 'mesin-peralatan',
            'title' => 'Perdagangan Besar Mesin, Peralatan dan Perlengkapan Lainnya',
            'description' => 'Perdagangan besar mesin, peralatan, dan perlengkapan lainnya untuk industri, bengkel, dan usaha menengah hingga besar. Pembelian dapat dilakukan dalam partai besar dengan ketersediaan barang yang terjaga.',
            'image' => 'images/home/layanan-mesin.jpg',
            'products' => [
                'Mesin industri dan mesin produksi',
                'Peralatan bengkel dan perkakas kerja',
                'Genset dan pompa air',
                'Kompresor dan peralatan angin',
                'Suku cadang dan perlengkapan mesin',
                'Peralatan keselamatan kerja',
            ],
        ],
        [
            'slug' => 'karet-plastik',
            'title' => 'Perdagangan Besar Karet dan Plastik dalam Bentuk Dasar',
            'description' => 'Perdagangan besar karet dan plastik dalam bentuk dasar untuk kebutuhan produksi. Kualitas bahan kami jaga agar proses produksi pelanggan berjalan lancar dan hasilnya konsisten.',
            'image' => 'images/home/layanan-karet-plastik.jpg',
            'products' => [
                'Karet lembaran dan karet gelang',
                'Pellet dan biji plastik',
                'Plastik lembaran dan film plastik',
                'Resin dan bahan baku plastik',
                'Selang dan pipa karet',
                'Bahan baku untuk industri kemasan',
            ],
        ],
        [
            'slug' => 'peralatan-dapur',
            'title' => 'Perdagangan Besar Peralatan Masak, Peralatan Dapur, dan Elektronik Rumah Tangga',
            'description' => 'Perdagangan besar peralatan masak, peralatan dapur, dan elektronik rumah tangga untuk toko, distributor, dan usaha katering.',
            'image' => 'images/home/layanan-peralatan-dapur.jpg',
            'products' => [
                'Panci, wajan, dan alat masak',
                'Peralatan makan dan minum',
                'Peralatan dapur stainless steel',
                'Kompor dan peralatan memasak',
                'Blender, rice cooker, dan dispenser',
                'Kulkas dan elektronik rumah tangga',
            ],
        ],
        [
            'slug' => 'komputer',
            'title' => 'Perdagangan Besar Komputer dan Perlengkapan Komputer',
            'description' => 'Perdagangan besar komputer dan perlengkapan komputer untuk kebutuhan kantor, instansi, dan usaha, cocok untuk pengadaan dalam jumlah banyak dengan harga wajar.',
            'image' => 'images/home/layanan-komputer.jpg',
            'products' => [
                'Laptop dan komputer desktop',
                'Monitor dan printer',
                'Keyboard, mouse, dan aksesori',
                'Penyimpanan data dan memori',
                'Perangkat jaringan dan kabel',
                'Tinta, toner, dan perlengkapan cetak',
            ],
        ],
        [
            'slug' => 'berbagai-barang',
            'title' => 'Perdagangan Besar Berbagai Macam Barang',
            'description' => 'Perdagangan besar berbagai macam barang untuk memenuhi beragam kebutuhan usaha pelanggan dalam satu pemasok, tanpa perlu mencari ke banyak tempat.',
            'image' => 'images/home/layanan-berbagai-barang.jpg',
            'products' => [
                'Perlengkapan kantor dan alat tulis',
                'Perlengkapan rumah tangga',
                'Peralatan elektronik umum',
                'Barang kebutuhan usaha dan toko',
                'Barang pesanan khusus sesuai permintaan',
            ],
        ],
        [
            'slug' => 'laboratorium-kedokteran',
            'title' => 'Perdagangan Besar Alat Laboratorium, Alat Farmasi, dan Alat Kedokteran untuk Manusia',
            'description' => 'Perdagangan besar alat laboratorium, alat farmasi, dan alat kedokteran untuk manusia. Kami membantu fasilitas kesehatan dan laboratorium memenuhi kebutuhan peralatannya dengan pengiriman tepat waktu.',
            'image' => 'images/home/layanan-laboratorium.jpg',
            'products' => [
                'Peralatan gelas dan instrumen laboratorium',
                'Mikroskop dan timbangan analitik',
                'Alat farmasi dan peracikan obat',
                'Alat kesehatan habis pakai',
                'Alat diagnostik dan pemeriksaan',
                'Perlengkapan sterilisasi',
            ],
        ],
        [
            'slug' => 'pakaian',
            'title' => 'Perdagangan Besar Pakaian',
            'description' => 'Perdagangan besar pakaian untuk toko, reseller, dan kebutuhan seragam usaha dengan pilihan yang beragam.',
            'image' => 'images/home/layanan-pakaian.jpg',
            'products' => [
                'Kaos dan kemeja',
                'Seragam kerja dan seragam usaha',
                'Celana dan rok',
                'Jaket dan pakaian luar',
                'Pakaian anak',
                'Pakaian dalam dan kaus kaki',
            ],
        ],
    ],

    // Isi halaman detail: gambar, judul, deskripsi, dan daftar produk memakai data 'items'.
    'detail_cta' => [
        'title' => 'Tertarik dengan Layanan Ini?',
        'description' => 'Hubungi kami untuk daftar harga dan ketersediaan barang.',
        'label' => 'Hubungi Kami',
        'image' => 'images/home/cta.jpg',
    ],

    'cta' => [
        'title' => 'Butuh Penawaran untuk Kebutuhan Anda?',
        'description' => 'Sampaikan kebutuhan Anda, tim kami akan segera menghubungi Anda.',
        'label' => 'Hubungi Kami',
        'image' => 'images/home/cta.jpg',
    ],

];
