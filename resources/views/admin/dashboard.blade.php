@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <x-admin.page-header title="Dashboard" />

    <p class="text-sm text-ink-800">Selamat datang, {{ auth()->user()->name }}. Pilih menu di atas untuk mengelola konten.</p>
@endsection
