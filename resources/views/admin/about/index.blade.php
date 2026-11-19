@extends('layouts.app')

@section('title', 'Tentang')

@section('content')
    <x-admin.page-header title="Tentang">
        <x-ui.button variant="secondary" :href="route('about')" target="_blank" rel="noopener noreferrer">Lihat Halaman</x-ui.button>
    </x-admin.page-header>

    <p class="-mt-3 mb-6 text-sm text-ink-800">Klik sebuah bagian untuk mengubah teks dan gambarnya.</p>

    {{-- Susunan sama seperti halaman Tentang; tiap bagian bisa diklik untuk diubah --}}
    <div class="overflow-hidden">
        <x-admin.section-preview :href="route('admin.home-sections.edit', \App\Enums\HomeSectionKey::AboutBanner)" label="Hero Section" compact>
            <x-web.hero-banner split :title="$content['about-banner']['title']" :description="$content['about-banner']['description']" :image="$content['about-banner']['image']" />
        </x-admin.section-preview>

        <x-admin.section-preview :href="route('admin.home-sections.edit', \App\Enums\HomeSectionKey::AboutBackground)" label="Latar Belakang">
            <x-web.about-background :data="$content['about-background']" />
        </x-admin.section-preview>

        {{-- Visi dan misi dua bagian terpisah di satu pita; masing-masing diklik sendiri --}}
        <section class="relative overflow-hidden bg-gold-50 py-24 md:py-32">
            <x-web.line-backdrop />

            <div class="relative mx-auto grid max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-2 lg:gap-10 lg:px-8">
                <x-admin.section-preview :href="route('admin.home-sections.edit', \App\Enums\HomeSectionKey::AboutVision)" label="Visi" class="h-full">
                    <x-web.about-vision class="h-full" :title="$content['about-vision']['title']" :text="$content['about-vision']['text']" />
                </x-admin.section-preview>

                <x-admin.section-preview :href="route('admin.home-sections.edit', \App\Enums\HomeSectionKey::AboutMission)" label="Misi" class="h-full">
                    <x-web.about-mission class="h-full" :title="$content['about-mission']['title']" :items="$content['about-mission']['items']" />
                </x-admin.section-preview>
            </div>
        </section>

        <x-admin.section-preview :href="route('admin.home-sections.edit', \App\Enums\HomeSectionKey::AboutContact)" label="Kontak">
            <x-web.about-contact :data="$content['about-contact']" />
        </x-admin.section-preview>
    </div>
@endsection
