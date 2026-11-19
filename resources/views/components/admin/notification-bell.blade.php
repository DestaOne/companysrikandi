{{-- Props: count (jumlah aktivitas baru), activities (aktivitas baru terbaru untuk pratinjau), href (halaman Aktivitas lengkap), messageCount (jumlah pesan belum dibaca), messages (pesan belum dibaca terbaru), messagesHref (halaman Pesan) --}}
@props(['count' => 0, 'activities' => [], 'href', 'messageCount' => 0, 'messages' => [], 'messagesHref' => null])

@php($total = $count + $messageCount)

<div class="relative" x-data="notificationBell('{{ route('admin.activities.seen') }}', '{{ csrf_token() }}', {{ $count }}, {{ $messageCount }})" @click.outside="open = false" @keydown.escape.window="open = false">
    <button type="button" @click="toggle()" :aria-expanded="open" aria-haspopup="true" aria-expanded="false"
            aria-label="{{ $messageCount > 0 ? $messageCount.' pesan belum dibaca. ' : '' }}{{ $count > 0 ? $count.' aktivitas baru' : 'Aktivitas' }}"
            class="relative flex size-10 items-center justify-center rounded-full text-ink-800 outline-none transition hover:bg-gold-50 hover:text-gold-700 focus-visible:ring-2 focus-visible:ring-gold-500">
        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>

        {{-- Aktivitas hilang setelah lonceng dibuka; pesan baru hilang setelah pesannya dibuka --}}
        @if ($total > 0)
            <span x-show="total > 0" x-text="label" class="absolute -right-0.5 -top-0.5 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-600 px-1 text-[11px] font-bold leading-none text-white ring-2 ring-white">{{ $total > 99 ? '99+' : $total }}</span>
        @endif
    </button>

    {{-- Pratinjau aktivitas baru; daftar lengkapnya di halaman Aktivitas --}}
    <div x-show="open" x-cloak x-transition.origin.top.right
         class="absolute right-0 top-full z-30 mt-2 w-80 max-w-[calc(100vw-2rem)] overflow-hidden border border-gold-300/70 border-t-4 border-t-gold-500 bg-white shadow-lg">
        @if ($messageCount > 0)
            <p class="border-b border-gold-300/70 px-4 py-3 text-sm font-semibold text-ink">{{ $messageCount }} pesan belum dibaca</p>

            <ul class="divide-y divide-gold-300/50 border-b border-gold-300/70">
                @foreach ($messages as $message)
                    <li>
                        <a href="{{ route('admin.messages.show', $message) }}" class="block px-4 py-3 text-sm leading-5 outline-none transition hover:bg-gold-50 focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-gold-500">
                            <p class="truncate"><span class="font-semibold text-ink">{{ $message->name }}</span> &middot; {{ $message->subject }}</p>
                            <p class="mt-1 text-xs text-ink-800">{{ $message->created_at->timezone(config('company.timezone'))->locale('id')->translatedFormat('d M Y') }}, {{ $message->created_at->timezone(config('company.timezone'))->format('H:i') }}</p>
                        </a>
                    </li>
                @endforeach
            </ul>

            @if ($messageCount > $messages->count())
                <a href="{{ $messagesHref }}" class="block border-b border-gold-300/70 px-4 py-2 text-xs text-ink-800 hover:text-gold-700">dan {{ $messageCount - $messages->count() }} pesan lainnya</a>
            @endif
        @endif

        <p class="border-b border-gold-300/70 px-4 py-3 text-sm font-semibold text-ink">
            {{ $count > 0 ? $count.' aktivitas baru' : 'Aktivitas' }}
        </p>

        <ul class="max-h-80 divide-y divide-gold-300/50 overflow-y-auto">
            @forelse ($activities as $activity)
                <li class="px-4 py-3 text-sm leading-5">
                    <p class="wrap-anywhere"><span class="font-semibold text-ink">{{ $activity->user?->name ?? 'Pengunjung' }}</span> {{ $activity->summary }}</p>
                    <p class="mt-1 text-xs text-ink-800">
                        {{ $activity->occurred_at->locale('id')->translatedFormat('d M Y') }}, {{ $activity->occurred_at->format('H:i') }}
                        @if ($activity->page)
                            &middot; {{ $activity->page }}
                        @endif
                    </p>
                </li>
            @empty
                <li class="px-4 py-6 text-center text-sm text-ink-800">Belum ada yang terbaru.</li>
            @endforelse
        </ul>

        @if ($count > $activities->count())
            <p class="border-t border-gold-300/50 px-4 py-2 text-xs text-ink-800">dan {{ $count - $activities->count() }} aktivitas baru lainnya</p>
        @endif

        <a href="{{ $href }}" class="block border-t border-gold-300/70 bg-gold-50/60 px-4 py-3 text-center text-sm font-semibold text-ink outline-none transition hover:text-gold-700 focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-gold-500">Lihat semua aktivitas</a>
    </div>
</div>
