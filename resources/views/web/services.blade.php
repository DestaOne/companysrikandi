@extends('layouts.public')

@section('title', 'Layanan | '.config('company.name'))

@section('content')
    <main>
        <x-web.hero-banner split :title="$page['banner']['title']" :description="$page['banner']['description']" :image="$page['banner']['image']" />

        <section class="relative overflow-hidden bg-white py-16 md:py-24">
            <x-web.line-backdrop />

            <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <p class="text-sm font-semibold uppercase tracking-widest text-gold-700">{{ $page['intro']['eyebrow'] }}</p>
                    <h2 class="mt-3 text-balance text-3xl font-semibold tracking-tight sm:text-4xl">{{ $page['intro']['title'] }}</h2>
                    <span class="bg-gold-metal mx-auto mt-5 block h-0.5 w-16"></span>
                </div>

                {{-- Selang-seling: ganjil gambar di kiri, genap gambar di kanan; "Lihat Detail" membuka pratinjau gambar --}}
                <div class="mt-16 space-y-20 md:space-y-32">
                    @foreach ($page['items'] as $service)
                        <article x-data class="grid overflow-hidden lg:w-[86%] {{ $loop->odd ? ['lg:ml-0', 'lg:ml-16', 'lg:ml-6', 'lg:ml-24'][intdiv($loop->index, 2) % 4] : ['lg:ml-auto lg:mr-10', 'lg:ml-auto lg:mr-0', 'lg:ml-auto lg:mr-20'][intdiv($loop->index, 2) % 3] }} border border-gold-300 border-t-4 border-t-gold-500 bg-white shadow-md lg:grid-cols-2">
                            {{-- Gambar mengisi kotak berukuran tetap (di-crop) agar tinggi card seragam walau rasio fotonya berbeda --}}
                            <div class="relative aspect-[4/3] lg:aspect-auto lg:min-h-[22rem] {{ $loop->even ? 'lg:order-last' : '' }}">
                                <x-ui.zoomable :src="asset($service['image'])" :title="$service['title']" class="absolute inset-0">
                                    <img src="{{ asset($service['image']) }}" alt="{{ $service['title'] }}" loading="lazy" class="size-full object-cover">
                                </x-ui.zoomable>
                            </div>

                            <div class="flex flex-col justify-center p-6 sm:p-10">
                                <h3 class="text-balance text-xl font-semibold tracking-tight sm:text-2xl">{{ $service['title'] }}</h3>
                                <span class="bg-gold-metal mt-5 block h-0.5 w-16"></span>
                                <p class="mt-5 text-base leading-8 text-ink-800">{{ $service['description'] }}</p>
                                <button type="button"
                                        @click="$dispatch('open-image', { src: @js(asset($service['image'])), title: @js($service['title']) })"
                                        class="group/detail mt-6 inline-flex items-center gap-2 self-start text-sm font-semibold text-ink hover:text-gold-700">
                                    Lihat Detail
                                    <svg class="size-4 transition-transform group-hover/detail:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                                </button>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <x-web.cta-banner :title="$page['cta']['title']" :description="$page['cta']['description']" :image="$page['cta']['image']" :href="route('about').'#kontak'" :label="$page['cta']['label']" />
    </main>
@endsection
