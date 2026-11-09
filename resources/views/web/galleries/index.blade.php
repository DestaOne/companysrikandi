@extends('layouts.public')

@section('title', 'Galeri | '.config('company.name'))

@section('content')
    <main class="mx-auto max-w-7xl px-4 pb-20 pt-28 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-semibold tracking-tight sm:text-4xl">Galeri</h1>

        @if ($galleries->isEmpty())
            <p class="mt-8 text-ink-800">Galeri belum tersedia.</p>
        @else
            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($galleries as $gallery)
                    <figure class="overflow-hidden rounded-xl bg-white shadow ring-1 ring-gold-300/60">
                        <img src="{{ asset('storage/'.$gallery->image) }}" alt="{{ $gallery->title }}" loading="lazy" class="aspect-[4/3] w-full object-cover">
                        <figcaption class="p-4">
                            <p class="font-semibold">{{ $gallery->title }}</p>
                            @if ($gallery->description)
                                <p class="mt-1 line-clamp-2 text-sm text-ink-800">{{ $gallery->description }}</p>
                            @endif
                        </figcaption>
                    </figure>
                @endforeach
            </div>

            <div class="mt-8">{{ $galleries->links() }}</div>
        @endif
    </main>
@endsection
