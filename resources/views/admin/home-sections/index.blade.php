@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <x-admin.page-header title="Home">
        <x-ui.button variant="secondary" :href="route('home')" target="_blank" rel="noopener noreferrer">Lihat Halaman</x-ui.button>
    </x-admin.page-header>

    <p class="-mt-3 mb-6 text-sm text-ink-800">Klik sebuah bagian untuk mengubah teks dan gambarnya.</p>

    {{-- Susunan sama seperti halaman Home; tiap bagian bisa diklik untuk diubah --}}
    <div class="overflow-hidden">
        @foreach (\App\Enums\HomeSectionKey::forPage('home') as $section)
            @php($data = $content[$section->value])

            <x-admin.section-preview :href="route('admin.home-sections.edit', $section)" :label="$section->label()" :compact="$section === \App\Enums\HomeSectionKey::Hero">
                @switch($section)
                    @case(\App\Enums\HomeSectionKey::Hero)
                        <x-web.hero-banner split :title="$data['title']" :description="$data['description']" :image="$data['image']" />
                        @break

                    @case(\App\Enums\HomeSectionKey::Services)
                        <div class="relative overflow-hidden bg-white py-16">
                            <x-web.line-backdrop />

                            <div class="relative mx-auto max-w-2xl px-4 text-center">
                                <p class="text-xs font-bold uppercase tracking-[0.2em] text-gold-700">{{ $data['eyebrow'] }}</p>
                                <h2 class="mt-3 text-balance text-3xl font-semibold tracking-tight text-ink sm:text-4xl">{{ $data['title'] }}</h2>
                            </div>
                            <x-web.service-coverflow :items="$data['items']" :href="url('/layanan')" :autoplay="false" class="relative mt-10" />
                        </div>
                        @break

                    @case(\App\Enums\HomeSectionKey::Features)
                        <div class="relative overflow-hidden bg-gold-50 px-4 py-16">
                            <x-web.line-backdrop />

                            <div class="relative mx-auto max-w-7xl">
                                <div class="mx-auto max-w-2xl text-center">
                                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-gold-700">{{ $data['eyebrow'] }}</p>
                                    <h2 class="mt-3 text-balance text-3xl font-semibold tracking-tight text-ink sm:text-4xl">{{ $data['title'] }}</h2>
                                </div>
                                <x-web.feature-accordion :items="$data['items']" class="mt-12" />
                            </div>
                        </div>
                        @break

                    @case(\App\Enums\HomeSectionKey::Cta)
                        <x-web.cta-banner :title="$data['title']" :description="$data['description']" :image="$data['image']" href="#" :label="$data['label']" />
                        @break
                @endswitch
            </x-admin.section-preview>
        @endforeach
    </div>
@endsection
