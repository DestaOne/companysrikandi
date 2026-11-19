@extends('layouts.app')

@section('title', 'Ubah Layanan')

@section('content')
    <x-admin.page-header title="Ubah Layanan" :back="route('admin.services.index')" />

    <x-admin.form :action="route('admin.services.update', $service)" method="PUT" :cancel="route('admin.services.index')">
        @include('admin.services._form')
    </x-admin.form>
@endsection
