@extends('layouts.app')

@section('title', 'Ubah '.$section->label())
@section('menu', $section->page())

@section('content')
    <x-admin.page-header :title="'Ubah '.$section->label()" :back="$section->hasItems() ? route('admin.home-sections.edit', $section) : route($section->indexRoute())" />

    <x-admin.form :action="route('admin.home-sections.update', $section)" method="PUT" :cancel="$section->hasItems() ? route('admin.home-sections.edit', $section) : route($section->indexRoute())">
        @foreach ($section->fields() as $name => $field)
            @if ($field['type'] === 'lines')
                @php($lines = implode("\n", $content[$name]))
                <x-ui.textarea :name="$name" :label="$field['label']" :value="old($name, $lines)" rows="8" required maxlength="{{ $field['max'] }}" hint="Tekan Enter untuk pindah ke butir berikutnya." />
            @elseif ($field['type'] === 'textarea')
                <x-ui.textarea :name="$name" :label="$field['label']" :value="old($name, $content[$name])" required maxlength="{{ $field['max'] }}" hint="Maksimal {{ $field['max'] }} karakter." />
            @else
                <x-ui.input :name="$name" :label="$field['label']" :value="old($name, $content[$name])" required maxlength="{{ $field['max'] }}" />
            @endif
        @endforeach

        @if ($section->picksServices())
            <div class="space-y-3">
                <div>
                    <h2 class="text-base font-semibold text-ink">Layanan yang ditampilkan</h2>
                    <p class="text-xs text-ink-800/80">Pilih empat layanan berbeda dari daftar Layanan yang berstatus Terbit.</p>
                </div>

                @if (count($pickerItems) < 4)
                    <p class="border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">Butuh minimal 4 layanan berstatus Terbit. Saat ini baru {{ count($pickerItems) }}. Tambahkan atau terbitkan layanan di menu Layanan.</p>
                @else
                    <div class="grid items-start gap-4 sm:grid-cols-2">
                        @foreach ($selectedIds as $index => $selectedId)
                            <x-admin.service-picker :index="$index" :items="$pickerItems" :selected="old('service_ids.'.$index, $selectedId)" />
                        @endforeach
                    </div>
                @endif

                @error('service_ids')
                    <p class="text-sm font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>
        @endif

        @if ($section->hasImage())
            <x-ui.file-upload name="image" label="Gambar (kosongkan jika tidak diganti)" :current="asset($content['image'])" />
        @endif
    </x-admin.form>
@endsection
