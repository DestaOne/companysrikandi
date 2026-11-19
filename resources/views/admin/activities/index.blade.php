@extends('layouts.app')

@section('title', 'Aktivitas')

@section('content')
    <x-admin.page-header title="Aktivitas" />

    <p class="-mt-3 mb-6 text-sm text-ink-800">Riwayat semua aktivitas: siapa melakukan apa, serta tanggal dan jamnya. Yang terbaru ada di atas.</p>

    <x-admin.table>
        <x-slot:head>
            <tr>
                <th class="w-48 px-4 py-3">Admin</th>
                <th class="w-56 px-4 py-3">Waktu</th>
                <th class="px-4 py-3">Aktivitas</th>
                <th class="w-40 px-4 py-3">Halaman diubah</th>
            </tr>
        </x-slot:head>

        @forelse ($activities as $activity)
            <tr class="align-top">
                <td class="px-4 py-3 font-medium">{{ $activity->user?->name ?? 'Pengunjung' }}</td>
                <td class="px-4 py-3">
                    <time datetime="{{ $activity->created_at->toIso8601String() }}">
                        <span class="block font-semibold text-ink">{{ $activity->occurred_at->locale('id')->translatedFormat('d F Y') }}</span>
                        <span class="text-ink-800">{{ $activity->occurred_at->format('H:i:s T') }}</span>
                    </time>
                </td>
                <td class="px-4 py-3 leading-6">
                    {{ Str::ucfirst($activity->summary) }}
                    @if ($activity->changed_fields)
                        <span class="block text-xs text-ink-800">Yang diubah: {{ $activity->changed_fields }}</span>
                    @endif
                </td>
                <td class="px-4 py-3">{{ $activity->page ?? '-' }}</td>
            </tr>
        @empty
            <tr><td colspan="4" class="px-4 py-10 text-center text-ink-800">Belum ada aktivitas.</td></tr>
        @endforelse
    </x-admin.table>

    <div class="mt-4">{{ $activities->links() }}</div>
@endsection
