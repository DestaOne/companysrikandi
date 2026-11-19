@extends('layouts.app')

@section('title', 'Logo')

@section('content')
    <x-admin.page-header title="Logo" />

    <p class="-mt-3 mb-6 text-sm text-ink-800">Ganti logo yang tampil di website. Kosongkan kolom yang tidak ingin diganti.</p>

    <x-admin.form :action="route('admin.branding.update')" method="PUT" :cancel="route('admin.dashboard')">
        @foreach ($fields as $name => $field)
            <x-ui.file-upload :name="$name" :label="$field['label']" :current="asset($content[$name])" :hint="$field['hint']" contain :dark="$field['dark'] ?? false">
                {{-- Form terpisah (di bawah) karena form di dalam form tidak valid; tombol menunjuknya lewat atribut form --}}
                @if ($content[$name] !== $defaults[$name])
                    <x-slot:actions>
                        <x-ui.button variant="secondary" type="submit" form="reset-{{ $name }}">Kembalikan ke bawaan</x-ui.button>
                    </x-slot:actions>
                @endif
            </x-ui.file-upload>
        @endforeach
    </x-admin.form>

    @foreach ($fields as $name => $field)
        <form id="reset-{{ $name }}" method="POST" action="{{ route('admin.branding.reset', $name) }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
@endsection
