{{-- Props: urls (array: gmail = tautan Gmail web, mailto = tautan aplikasi email; keduanya sudah berisi data form), seconds (jeda sebelum dialihkan otomatis); tampil sebagai teks di atas form kontak --}}
@props(['urls', 'seconds' => 3])

<div x-data="mailHandoff(@js($urls), {{ $seconds }})" role="status"
     class="mb-4 border-l-4 border-gold-500 bg-gold-50 px-4 py-3 text-sm leading-6 text-ink-800">
    <p class="font-semibold text-ink">Pesan Anda terkirim.</p>
    <p>
        Tidak dialihkan otomatis? Kirim manual lewat
        <a href="{{ $urls['gmail'] }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-gold-700 underline hover:text-gold-900">email web (Gmail)</a>
        atau
        <a href="{{ $urls['mailto'] }}" class="font-semibold text-gold-700 underline hover:text-gold-900">aplikasi email</a>.
        Data Anda sudah terisi otomatis.
    </p>
</div>
