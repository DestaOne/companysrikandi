@extends('layouts.public')

@section('title', 'Tentang Kami | '.config('company.name'))

@section('content')
    <main>
        <x-web.hero-banner split :title="$banner['title']" :description="$banner['description']" :image="$banner['image']" />

        {{-- Latar belakang: gambar kiri, teks kanan --}}
        <x-web.about-background :data="$background" />

        {{-- Visi dan misi --}}
        <section class="relative overflow-hidden bg-gold-50 py-24 md:py-32">
            <x-web.line-backdrop />
            <x-web.wave flip class="text-white" />

            <div class="relative mx-auto grid max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-2 lg:gap-10 lg:px-8">
                <x-web.about-vision :title="$vision['title']" :text="$vision['text']" />
                <x-web.about-mission :title="$mission['title']" :items="$mission['items']" />
            </div>

            <x-web.wave class="text-white" />
        </section>

        {{-- Kontak: info kiri, form kanan. scroll-mt = tinggi navbar (5rem) dikurangi padding atas section, jadi judul dan form tampil pas di bawah navbar saat tombol "Hubungi Kami" ditekan --}}
        <x-web.about-contact :data="$contact" id="kontak" class="scroll-mt-8 md:scroll-mt-2" />
    </main>
@endsection
