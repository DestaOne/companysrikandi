@extends('layouts.public')

@section('title', $service['title'].' | '.config('company.name'))

@section('content')
    <main>
        <section class="relative overflow-hidden bg-white pb-16 pt-28 md:pb-24 md:pt-36">
            <x-web.line-backdrop />

            <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <a href="{{ route('services') }}" class="inline-flex items-center gap-3 text-lg font-semibold text-ink hover:text-gold-700 sm:text-xl">
                    <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
                    Semua Layanan
                </a>

                <div class="mt-8 grid items-start gap-10 lg:grid-cols-2 lg:gap-14">
                    <div class="relative aspect-[4/3] overflow-hidden border border-gold-300 border-t-4 border-t-gold-500 shadow-md">
                        <x-ui.zoomable :src="asset($service['image'])" :title="$service['title']" class="absolute inset-0">
                            <img src="{{ asset($service['image']) }}" alt="{{ $service['title'] }}" class="size-full object-cover">
                        </x-ui.zoomable>
                    </div>

                    <div>
                        <h1 class="text-balance text-3xl font-semibold tracking-tight sm:text-4xl">{{ $service['title'] }}</h1>
                        <p class="mt-5 text-base leading-8 text-ink-800">{{ $service['description'] }}</p>

                        <h2 class="mt-10 text-lg font-semibold tracking-tight">Barang yang Tersedia</h2>
                        <ul class="mt-4 grid gap-x-8 gap-y-3 sm:grid-cols-2">
                            @foreach ($service['products'] as $product)
                                <li class="flex items-start gap-3 text-base text-ink-800">
                                    <span class="mt-2.5 size-1.5 shrink-0 rounded-full bg-gold-500" aria-hidden="true"></span>
                                    {{ $product }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <x-web.cta-banner :title="$cta['title']" :description="$cta['description']" :image="$cta['image']" :href="route('about').'#kontak'" :label="$cta['label']" />
    </main>
@endsection
