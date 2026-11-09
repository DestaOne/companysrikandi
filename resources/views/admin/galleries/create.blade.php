@extends('layouts.app')

@section('title', 'Tambah Galeri')

@section('content')
    <x-admin.page-header title="Tambah Galeri" />

    <form method="POST" action="{{ route('admin.galleries.store') }}" enctype="multipart/form-data" class="max-w-xl">
        @include('admin.galleries._form')
    </form>
@endsection
