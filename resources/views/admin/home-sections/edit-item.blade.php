@extends('layouts.app')

@section('title', 'Ubah Kartu '.($index + 1))
@section('menu', $section->page())

@section('content')
    <x-admin.page-header :title="$section->label().': Kartu '.($index + 1)" :back="route('admin.home-sections.edit', $section)" />

    <x-admin.form :action="route('admin.home-sections.items.update', [$section, $index])" method="PUT" :cancel="route('admin.home-sections.edit', $section)">
        @foreach ($section->itemFields() as $name => $field)
            @if ($field['type'] === 'textarea')
                <x-ui.textarea :name="$name" :label="$field['label']" :value="old($name, $item[$name])" required maxlength="{{ $field['max'] }}" hint="Maksimal {{ $field['max'] }} karakter." />
            @else
                <x-ui.input :name="$name" :label="$field['label']" :value="old($name, $item[$name])" required maxlength="{{ $field['max'] }}" />
            @endif
        @endforeach

        <x-ui.file-upload name="image" label="Gambar (kosongkan jika tidak diganti)" :current="asset($item['image'])" />
    </x-admin.form>
@endsection
