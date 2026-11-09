{{-- Props: slot head (isi <tr> header), slot default (baris <tr>) --}}
<div class="overflow-x-auto rounded-lg bg-white ring-1 ring-gold-300/60">
    <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-gold-100 text-left text-sm']) }}>
        <thead class="bg-gold-50 text-xs uppercase tracking-wide text-ink-800">
            {{ $head }}
        </thead>
        <tbody class="divide-y divide-gold-100">
            {{ $slot }}
        </tbody>
    </table>
</div>
