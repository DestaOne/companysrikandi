@extends('layouts.app')

@section('title', 'Tambah Layanan')

@section('content')
    <x-admin.page-header title="Tambah Layanan" :back="route('admin.services.index')" />

    <x-admin.form :action="route('admin.services.store')" :cancel="route('admin.services.index')">
        @include('admin.services._form')
    </x-admin.form>
@endsection
