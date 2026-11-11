@extends('layouts.public')

@section('title', 'Tentang Kami | '.config('company.name'))

@section('content')
    <main>
        <x-web.hero-banner split :title="$about['banner']['title']" :description="$about['banner']['description']" :image="$about['banner']['image']" />

        {{-- Latar belakang: gambar kiri, teks kanan --}}
        <section class="relative overflow-hidden bg-white py-16 md:py-24">
            <div class="absolute -left-24 top-10 -z-0 size-72 rounded-full border border-gold-300/50"></div>
            <div class="absolute -left-12 top-22 -z-0 size-72 rounded-full border border-gold-300/30"></div>

            <div class="relative mx-auto grid max-w-7xl items-center gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:gap-16 lg:px-8">
                <div class="relative">
                    <div class="absolute -bottom-4 -right-4 size-full border-2 border-gold-300"></div>
                    <x-ui.zoomable :src="asset($about['background']['image'])" title="Latar belakang perusahaan" class="relative">
                        <img src="{{ asset($about['background']['image']) }}" alt="Latar belakang perusahaan" loading="lazy" class="aspect-[4/3] w-full object-cover shadow-lg">
                    </x-ui.zoomable>
                </div>

                <div>
                    <p class="text-sm font-semibold uppercase tracking-widest text-gold-700">{{ $about['background']['eyebrow'] }}</p>
                    <h2 class="mt-3 text-balance text-3xl font-semibold tracking-tight sm:text-4xl">{{ $about['background']['title'] }}</h2>
                    <span class="bg-gold-metal mt-5 block h-0.5 w-16"></span>
                    <div class="mt-6 space-y-4 text-base leading-8 text-ink-800">
                        @foreach ($about['background']['paragraphs'] as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- Visi dan misi --}}
        <section class="relative bg-gold-50 py-24 md:py-32">
            <x-web.wave flip class="text-white" />

            <div class="mx-auto grid max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-2 lg:gap-10 lg:px-8">
                <article class="flex flex-col border border-gold-300 border-t-4 border-t-gold-500 bg-white p-8 shadow-md sm:p-10">
                    <div class="flex items-center gap-4">
                        <svg class="size-9 shrink-0 text-gold-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                        <h2 class="text-3xl font-bold tracking-tight text-ink">{{ $about['vision']['title'] }}</h2>
                    </div>
                    <span class="bg-gold-metal mt-6 block h-0.5 w-16"></span>
                    <p class="mt-6 text-lg font-medium leading-8 text-ink">{{ $about['vision']['text'] }}</p>
                </article>

                <article class="flex flex-col border border-gold-300 border-t-4 border-t-gold-500 bg-white p-8 shadow-md sm:p-10">
                    <div class="flex items-center gap-4">
                        <svg class="size-9 shrink-0 text-gold-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 21V4M4 4h12l-2 4 2 4H4"/></svg>
                        <h2 class="text-3xl font-bold tracking-tight text-ink">{{ $about['mission']['title'] }}</h2>
                    </div>
                    <span class="bg-gold-metal mt-6 block h-0.5 w-16"></span>
                    <ul class="mt-6 space-y-4 text-lg leading-8 text-ink">
                        @foreach ($about['mission']['items'] as $item)
                            <li class="flex gap-4">
                                <span class="bg-gold-metal mt-3 block size-2.5 shrink-0 rounded-full"></span>
                                <span class="font-medium">{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </article>
            </div>

            <x-web.wave class="text-white" />
        </section>

        {{-- Kontak: info kiri, form kanan --}}
        <section id="kontak" class="scroll-mt-20 bg-white py-16 md:py-24">
            <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:gap-16 lg:px-8">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-widest text-gold-700">{{ $about['contact']['eyebrow'] }}</p>
                    <h2 class="mt-3 text-3xl font-semibold tracking-tight sm:text-4xl">{{ $about['contact']['title'] }}</h2>
                    <span class="bg-gold-metal mt-5 block h-0.5 w-16"></span>
                    <p class="mt-6 text-base leading-8 text-ink-800">{{ $about['contact']['description'] }}</p>

                    <dl class="mt-8 space-y-6 text-sm">
                        <div class="flex gap-4">
                            <dt class="flex size-10 shrink-0 items-center justify-center rounded-full bg-gold-50 text-gold-700 ring-1 ring-gold-300">
                                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z"/><circle cx="12" cy="10" r="2.5"/></svg>
                                <span class="sr-only">Alamat</span>
                            </dt>
                            <dd class="leading-7 text-ink-800">{{ config('company.address') }}</dd>
                        </div>
                        <div class="flex gap-4">
                            <dt class="flex size-10 shrink-0 items-center justify-center rounded-full bg-gold-50 text-gold-700 ring-1 ring-gold-300">
                                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2Z"/></svg>
                                <span class="sr-only">Telepon</span>
                            </dt>
                            <dd class="leading-10 text-ink-800"><a href="tel:{{ config('company.phone') }}" class="hover:text-gold-700">{{ config('company.phone') }}</a></dd>
                        </div>
                        <div class="flex gap-4">
                            <dt class="flex size-10 shrink-0 items-center justify-center rounded-full bg-gold-50 text-gold-700 ring-1 ring-gold-300">
                                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                                <span class="sr-only">Email</span>
                            </dt>
                            <dd class="break-all leading-10 text-ink-800"><a href="mailto:{{ config('company.email') }}" class="hover:text-gold-700">{{ config('company.email') }}</a></dd>
                        </div>
                    </dl>
                </div>

                <form method="POST" action="{{ route('about.contact.store') }}" class="space-y-5 bg-white p-6 shadow-lg ring-1 ring-gold-300/60 sm:p-8">
                    @csrf
                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-ui.input name="name" label="Nama" :value="old('name')" required autocomplete="name" />
                        <x-ui.input name="email" type="email" label="Email" :value="old('email')" required autocomplete="email" />
                    </div>
                    <x-ui.input name="phone" type="tel" label="No. Telepon / WhatsApp" :value="old('phone')" required autocomplete="tel" />
                    <x-ui.input name="subject" label="Subjek" :value="old('subject')" required />
                    <x-ui.textarea name="message" label="Isi Pesan" :value="old('message')" rows="6" required />
                    <x-ui.button type="submit" class="w-full sm:w-auto">Kirim Pesan</x-ui.button>
                </form>
            </div>
        </section>

        <x-web.cta-banner :title="$about['cta']['title']" :description="$about['cta']['description']" :image="$about['cta']['image']" :href="route('about').'#kontak'" :label="$about['cta']['label']" />
    </main>
@endsection
