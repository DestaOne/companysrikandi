@extends('layouts.app')

@section('title', 'Pesan')

@section('content')
    <x-admin.page-header title="Pesan" />

    <p class="-mt-3 mb-6 text-sm text-ink-800">Klik sebuah pesan untuk membaca dan membalasnya. Yang terbaru ada di atas.</p>

    <x-admin.table>
        <x-slot:head>
            <tr>
                <th class="w-24 px-4 py-3 text-center">Status</th>
                <th class="px-4 py-3">Nama</th>
                <th class="px-4 py-3">No. Telepon</th>
                <th class="px-4 py-3">Email</th>
                <th class="px-4 py-3">Isi Pesan</th>
                <th class="w-36 px-4 py-3">Tanggal Masuk</th>
                <th class="w-24 px-4 py-3 text-center">Hapus</th>
            </tr>
        </x-slot:head>

        @forelse ($messages as $message)
            {{-- Seluruh sel berisi tautan ke detail, jadi baris bisa diklik di mana saja kecuali tombol hapus --}}
            @php($detail = route('admin.messages.show', $message))
            <tr @class(['align-middle transition hover:bg-gold-50/60', 'font-semibold' => ! $message->read_at])>
                <td class="text-center"><a href="{{ $detail }}" class="block px-4 py-4"><x-ui.badge :variant="$message->read_at ? 'default' : 'danger'">{{ $message->read_at ? 'Dibaca' : 'Baru' }}</x-ui.badge></a></td>
                <td class="max-w-40"><a href="{{ $detail }}" class="block wrap-anywhere px-4 py-4 text-ink">{{ $message->name }}</a></td>
                <td><a href="{{ $detail }}" class="block whitespace-nowrap px-4 py-4 font-normal text-ink-800">{{ $message->phone }}</a></td>
                <td class="max-w-48"><a href="{{ $detail }}" class="block break-all px-4 py-4 font-normal text-ink-800">{{ $message->email }}</a></td>
                <td class="max-w-xs"><a href="{{ $detail }}" class="block wrap-anywhere px-4 py-4 font-normal leading-6 text-ink-800">{{ Str::limit($message->message, 80) }}</a></td>
                <td>
                    <a href="{{ $detail }}" class="block px-4 py-4 font-normal text-ink-800">
                        <time datetime="{{ $message->created_at->toIso8601String() }}">
                            <span class="block font-semibold text-ink">{{ $message->created_at->timezone(config('company.timezone'))->locale('id')->translatedFormat('d M Y') }}</span>
                            {{ $message->created_at->timezone(config('company.timezone'))->format('H:i') }}
                        </time>
                    </a>
                </td>
                <td class="px-4 py-4 text-center">
                    <x-ui.confirm-delete :action="route('admin.messages.destroy', $message)" :name="'delete-message-'.$message->id" message="Pesan ini akan dihapus dan tidak bisa dikembalikan." solid />
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="px-4 py-10 text-center text-ink-800">Belum ada pesan masuk.</td></tr>
        @endforelse
    </x-admin.table>

    <div class="mt-4">{{ $messages->links() }}</div>
@endsection
