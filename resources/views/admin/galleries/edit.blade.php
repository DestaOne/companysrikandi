@extends('layouts.app')

@section('title', 'Ubah Galeri')

@section('content')
    <x-admin.page-header title="Ubah Galeri" :back="route('admin.galleries.index')" />

    <x-admin.form :action="route('admin.galleries.update', $gallery)" method="PUT" :cancel="route('admin.galleries.index')">
        @include('admin.galleries._form')
    </x-admin.form>
@endsection
