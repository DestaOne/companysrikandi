@extends('layouts.public')

@section('title', 'Galeri | '.config('company.name'))

@section('content')
    <main>
        <x-web.hero-banner split :title="$gallery['hero']['title']" :description="$gallery['hero']['description']" :image="$gallery['hero']['image']" />

        <section class="relative overflow-hidden bg-white py-16 md:py-24">
            <x-web.line-backdrop />

            <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                @if ($galleries->isEmpty())
                    <p class="text-center text-ink-800">{{ $gallery['empty'] }}</p>
                @else
                    <x-web.gallery-grid>
                        @foreach ($galleries as $item)
                            <x-web.gallery-tile :image="asset('storage/'.$item->image)" :title="$item->title" :description="$item->description" />
                        @endforeach
                    </x-web.gallery-grid>
                @endif
            </div>
        </section>
    </main>
@endsection
