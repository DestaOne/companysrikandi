@extends('layouts.guest')

@section('title', 'Masuk')

@section('aside')
    <p class="text-[0.65rem] font-semibold uppercase tracking-widest text-gold-300">Area Admin</p>
    <p class="mt-4 font-serif text-5xl leading-tight text-white">Selamat datang</p>
    <p class="mt-6 max-w-xs text-base font-medium leading-8 text-white">Kelola konten website {{ config('company.name') }} dari satu tempat.</p>
@endsection

@section('content')
    <h1 class="mb-8 font-serif text-6xl leading-tight">Login <span class="bg-gold-metal bg-clip-text text-transparent">Admin</span></h1>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf
        <x-ui.input name="email" type="email" label="Email" :value="old('email')" size="lg" required autofocus autocomplete="username" />
        <x-ui.input name="password" type="password" label="Kata sandi" size="lg" required autocomplete="current-password" />

        <x-ui.checkbox name="remember" label="Ingat saya" :checked="old('remember')" />

        <x-ui.timed-button :seconds="$lockoutSeconds" size="lg" class="w-full">Masuk</x-ui.timed-button>
    </form>
@endsection
