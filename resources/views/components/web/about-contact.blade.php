{{-- Props: data (eyebrow, title, description, address, phone, email); setelah pesan terkirim, session 'mail_draft' memunculkan tawaran buka email; atribut tambahan diteruskan ke <section> --}}
@props(['data'])

<section {{ $attributes->class('relative overflow-hidden bg-white py-16 md:py-24') }}>
    <x-web.line-backdrop />

    <div class="relative mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:gap-16 lg:px-8">
        <div>
            <p class="text-base font-semibold uppercase tracking-widest text-gold-700">{{ $data['eyebrow'] }}</p>
            <h2 class="mt-3 text-4xl wrap-anywhere font-semibold tracking-tight sm:text-5xl">{{ $data['title'] }}</h2>
            <span class="bg-gold-metal mt-5 block h-0.5 w-16"></span>
            <p class="mt-6 wrap-anywhere text-lg leading-8 text-ink-800">{{ $data['description'] }}</p>

            <dl class="mt-10 space-y-7 text-base">
                <div class="flex gap-4">
                    <dt class="flex size-12 shrink-0 items-center justify-center rounded-full bg-gold-50 text-gold-700 ring-1 ring-gold-300">
                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 21s7-6.2 7-11a7 7 0 1 0-14 0c0 4.8 7 11 7 11Z"/><circle cx="12" cy="10" r="2.5"/></svg>
                        <span class="sr-only">Alamat</span>
                    </dt>
                    <dd class="text-lg leading-8 text-ink-800">{{ $data['address'] }}</dd>
                </div>
                <div class="flex gap-4">
                    <dt class="flex size-12 shrink-0 items-center justify-center rounded-full bg-gold-50 text-gold-700 ring-1 ring-gold-300">
                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2Z"/></svg>
                        <span class="sr-only">Telepon</span>
                    </dt>
                    <dd class="text-lg leading-12 text-ink-800"><a href="tel:{{ $data['phone'] }}" class="hover:text-gold-700">{{ $data['phone'] }}</a></dd>
                </div>
                <div class="flex gap-4">
                    <dt class="flex size-12 shrink-0 items-center justify-center rounded-full bg-gold-50 text-gold-700 ring-1 ring-gold-300">
                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                        <span class="sr-only">Email</span>
                    </dt>
                    <dd class="break-all text-lg leading-12 text-ink-800"><a href="mailto:{{ $data['email'] }}" class="hover:text-gold-700">{{ $data['email'] }}</a></dd>
                </div>
            </dl>
        </div>

        <div>
        @if (session('mail_draft'))
            <x-web.mail-handoff :urls="session('mail_draft')" />
        @endif

        <form method="POST" action="{{ route('about.contact.store') }}" class="space-y-5 bg-white p-6 shadow-lg ring-1 ring-gold-300/60 sm:p-8">
            @csrf
            <div class="grid gap-5 sm:grid-cols-2">
                <x-ui.input name="name" label="Nama" :value="old('name')" required autocomplete="name" />
                <x-ui.input name="email" type="email" label="Email" :value="old('email')" required autocomplete="email" />
            </div>
            <x-ui.input name="phone" type="tel" label="No. Telepon / WhatsApp" :value="old('phone')" required autocomplete="tel" />
            <x-ui.input name="subject" label="Subjek" :value="old('subject')" required />
            <x-ui.textarea name="message" label="Isi Pesan" :value="old('message')" rows="6" required />
            <x-ui.button type="submit" class="w-full sm:w-auto">Kirim Pesan</x-ui.button>
        </form>
        </div>
    </div>

</section>
