@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <x-ui.input name="email" type="email" label="Email" :value="old('email')" required autofocus autocomplete="username" />
        <x-ui.input name="password" type="password" label="Kata sandi" required autocomplete="current-password" />
        <x-ui.button type="submit" class="w-full">Masuk</x-ui.button>
    </form>
@endsection
