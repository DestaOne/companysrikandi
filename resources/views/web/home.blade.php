@extends('layouts.public')

@section('content')
    <main>
        <x-web.hero-banner split :title="$hero['title']" :description="$hero['description']" :image="$hero['image']">
            <x-slot:actions>
                <a href="{{ url('/layanan') }}"
                   class="bg-gold-sheen inline-flex h-12 items-center px-8 text-[15px] font-semibold text-ink outline-none transition hover:brightness-95 focus-visible:ring-2 focus-visible:ring-white">
                    Lihat Layanan
                </a>
            </x-slot:actions>
        </x-web.hero-banner>

        {{-- Layanan: coverflow --}}
        <section class="bg-white py-20" aria-labelledby="layanan-heading">
            <div class="mx-auto max-w-2xl px-4 text-center sm:px-6 lg:px-8">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-gold-700">{{ $services['eyebrow'] }}</p>
                <h2 id="layanan-heading" class="mt-3 text-balance text-3xl font-semibold tracking-tight text-ink sm:text-4xl">{{ $services['title'] }}</h2>
            </div>

            <x-web.service-coverflow :items="$services['items']" :href="url('/layanan')" class="mt-10" />
        </section>

        {{-- Keunggulan: panel foto yang melebar saat di-hover --}}
        <section class="bg-gold-50 px-4 py-20 sm:px-6 lg:px-8" aria-labelledby="keunggulan-heading">
            <div class="mx-auto max-w-7xl">
                <div class="mx-auto max-w-2xl text-center">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-gold-700">{{ $features['eyebrow'] }}</p>
                    <h2 id="keunggulan-heading" class="mt-3 text-balance text-3xl font-semibold tracking-tight text-ink sm:text-4xl">{{ $features['title'] }}</h2>
                </div>

                <x-web.feature-accordion :items="$features['items']" class="mt-12" />
            </div>
        </section>

        <x-web.cta-banner :title="$cta['title']" :description="$cta['description']" :image="$cta['image']" :href="route('about').'#kontak'" :label="$cta['label']" />
    </main>
@endsection
