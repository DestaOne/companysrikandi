@extends('layouts.public')

@section('title', 'Galeri | '.config('company.name'))

@section('content')
    <main>
        <x-web.hero-banner split :title="$gallery['hero']['title']" :description="$gallery['hero']['description']" :image="$gallery['hero']['image']" />

        <section class="relative overflow-hidden bg-white py-16 md:py-24">
            <div class="absolute -right-24 top-10 size-72 rounded-full border border-gold-300/50"></div>
            <div class="absolute -right-12 top-22 size-72 rounded-full border border-gold-300/30"></div>

            <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                @if ($galleries->isEmpty())
                    <p class="text-center text-ink-800">{{ $gallery['empty'] }}</p>
                @else
                    <div class="grid auto-rows-[9rem] grid-flow-dense grid-cols-2 gap-3 sm:auto-rows-[12rem] sm:gap-4 lg:grid-cols-4">
                        @foreach ($galleries as $item)
                            <x-web.gallery-tile :index="$loop->index" :image="asset('storage/'.$item->image)" :title="$item->title" :description="$item->description" />
                        @endforeach
                    </div>

                    <div class="mt-10">{{ $galleries->links() }}</div>
                @endif
            </div>
        </section>
    </main>
@endsection
