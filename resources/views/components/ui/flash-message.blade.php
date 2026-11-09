{{-- Tanpa props; menampilkan session 'success' dan 'error', bisa ditutup --}}
@foreach (['success' => 'bg-green-50 text-green-800 ring-green-200', 'error' => 'bg-red-50 text-red-800 ring-red-200'] as $type => $classes)
    @if (session($type))
        <div x-data="{ show: true }" x-show="show" role="alert"
             class="mb-4 flex items-start justify-between gap-4 rounded-md px-4 py-3 text-sm ring-1 {{ $classes }}">
            <p>{{ session($type) }}</p>
            <button type="button" x-on:click="show = false" aria-label="Tutup" class="font-bold leading-none">&times;</button>
        </div>
    @endif
@endforeach
