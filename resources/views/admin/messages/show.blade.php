@extends('layouts.app')

@section('title', 'Pesan')

@section('content')
    <x-admin.page-header title="Detail Pesan" :back="route('admin.messages.index')" />

    <div class="grid gap-4 sm:gap-6 lg:grid-cols-3">
        <section class="bg-white p-4 ring-1 ring-gold-300/60 sm:p-6 lg:col-span-2">
            <h2 class="wrap-anywhere text-lg font-semibold">{{ $message->subject }}</h2>
            <p class="mt-1 text-xs text-ink-800">
                Diterima {{ $message->created_at->timezone(config('company.timezone'))->locale('id')->translatedFormat('d F Y, H:i') }}
            </p>
            <span class="bg-gold-metal mt-3 block h-0.5 w-12"></span>

            <p class="mt-5 wrap-anywhere whitespace-pre-line leading-7 text-ink-800">{{ $message->message }}</p>
        </section>

        <aside class="space-y-4 sm:space-y-6">
            <section class="bg-white p-4 ring-1 ring-gold-300/60 sm:p-6">
                <h2 class="text-lg font-semibold">Data Pengirim</h2>
                <span class="bg-gold-metal mt-2 block h-0.5 w-12"></span>

                <dl class="mt-4 space-y-3 text-sm">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-ink-800/70">Nama</dt>
                        <dd class="wrap-anywhere">{{ $message->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-ink-800/70">Email</dt>
                        <dd class="break-all">{{ $message->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-ink-800/70">Telepon / WhatsApp</dt>
                        <dd>{{ $message->phone }}</dd>
                    </div>
                </dl>
            </section>

            <section class="bg-white p-4 ring-1 ring-gold-300/60 sm:p-6">
                <h2 class="text-lg font-semibold">Balas</h2>
                <span class="bg-gold-metal mt-2 block h-0.5 w-12"></span>
                <p class="mt-3 text-xs leading-5 text-ink-800">Pilih salah satu sesuai data yang diisi pengirim.</p>

                <div class="mt-4 flex flex-col gap-2">
                    <x-ui.button :href="$message->whatsapp_url" target="_blank" rel="noopener noreferrer">Balas lewat WhatsApp</x-ui.button>
                    <x-ui.button variant="secondary" :href="$message->reply_mail_url">Balas lewat Email</x-ui.button>
                </div>

                <div class="mt-5 border-t border-gold-300/50 pt-4">
                    <x-ui.confirm-delete :action="route('admin.messages.destroy', $message)" name="delete-message" message="Pesan ini akan dihapus dan tidak bisa dikembalikan.">Hapus pesan</x-ui.confirm-delete>
                </div>
            </section>
        </aside>
    </div>
@endsection
