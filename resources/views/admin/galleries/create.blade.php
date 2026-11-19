@extends('layouts.app')

@section('title', 'Tambah Galeri')

@section('content')
    <x-admin.page-header title="Tambah Galeri" :back="route('admin.galleries.index')" />

    <x-admin.form :action="route('admin.galleries.store')" :cancel="route('admin.galleries.index')">
        @include('admin.galleries._form')
    </x-admin.form>
@endsection
