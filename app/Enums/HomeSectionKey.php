<?php

namespace App\Enums;

enum HomeSectionKey: string
{
    case Hero = 'hero';
    case Services = 'services';
    case Features = 'features';
    case Cta = 'cta';
    case ServiceBanner = 'service-banner';
    case ServiceCta = 'service-cta';
    case AboutBanner = 'about-banner';
    case AboutBackground = 'about-background';
    case AboutVision = 'about-vision';
    case AboutMission = 'about-mission';
    case AboutContact = 'about-contact';
    case Branding = 'branding';

    public function label(): string
    {
        return match ($this) {
            self::Hero, self::ServiceBanner, self::AboutBanner => 'Hero Section',
            self::Services => 'Card Layanan',
            self::Features => 'Keunggulan',
            self::Cta, self::ServiceCta => 'Ajakan Menghubungi',
            self::AboutBackground => 'Latar Belakang',
            self::AboutVision => 'Visi',
            self::AboutMission => 'Misi',
            self::AboutContact => 'Kontak',
            self::Branding => 'Logo',
        };
    }

    /** Halaman tempat section ini tampil: home, services, atau about. */
    public function page(): string
    {
        return match ($this) {
            self::ServiceBanner, self::ServiceCta => 'services',
            self::AboutBanner, self::AboutBackground, self::AboutVision, self::AboutMission, self::AboutContact => 'about',
            self::Branding => 'site',
            default => 'home',
        };
    }

    /** Nama halaman tempat section ini tampil, sesuai nama menu admin. */
    public function pageLabel(): string
    {
        return match ($this->page()) {
            'services' => 'Layanan',
            'about' => 'Tentang',
            'site' => 'Sistem',
            default => 'Home',
        };
    }

    /**
     * @return array<int, self>
     */
    public static function forPage(string $page): array
    {
        return array_values(array_filter(self::cases(), fn (self $key): bool => $key->page() === $page));
    }

    /** Route daftar admin tempat section ini dipratinjau. */
    public function indexRoute(): string
    {
        return match ($this->page()) {
            'services' => 'admin.services.index',
            'about' => 'admin.about.index',
            'site' => 'admin.branding.edit',
            default => 'admin.home-sections.index',
        };
    }

    /** Kunci config berisi isi bawaan section ini. */
    public function configPath(): string
    {
        return match ($this) {
            self::ServiceBanner => 'service.banner',
            self::ServiceCta => 'service.cta',
            self::AboutBanner => 'about.banner',
            self::AboutBackground => 'about.background',
            self::AboutVision => 'about.vision',
            self::AboutMission => 'about.mission',
            self::AboutContact => 'about.contact',
            self::Branding => 'company.logos',
            default => 'home.'.$this->value,
        };
    }

    /**
     * Kolom teks tingkat atas: nama => label, tipe (input|textarea|lines), panjang maksimal.
     * Tipe "lines" adalah textarea yang disimpan sebagai daftar: satu baris = satu butir.
     *
     * @return array<string, array{label: string, type: string, max: int, rules?: array<int, string>}>
     */
    public function fields(): array
    {
        return match ($this) {
            self::Hero, self::ServiceBanner, self::AboutBanner => [
                'title' => ['label' => 'Judul', 'type' => 'input', 'max' => 150],
                'description' => ['label' => 'Teks', 'type' => 'textarea', 'max' => 500],
            ],
            self::Services, self::Features => [
                'eyebrow' => ['label' => 'Teks kecil di atas judul', 'type' => 'input', 'max' => 60],
                'title' => ['label' => 'Judul', 'type' => 'input', 'max' => 150],
            ],
            self::Cta, self::ServiceCta => [
                'title' => ['label' => 'Judul', 'type' => 'input', 'max' => 150],
                'description' => ['label' => 'Teks', 'type' => 'textarea', 'max' => 500],
                'label' => ['label' => 'Teks tombol', 'type' => 'input', 'max' => 40],
            ],
            self::AboutBackground => [
                'eyebrow' => ['label' => 'Teks kecil di atas judul', 'type' => 'input', 'max' => 60],
                'title' => ['label' => 'Judul', 'type' => 'input', 'max' => 150],
                'paragraphs' => ['label' => 'Isi (satu paragraf per baris)', 'type' => 'lines', 'max' => 3000],
            ],
            self::AboutVision => [
                'title' => ['label' => 'Judul', 'type' => 'input', 'max' => 60],
                'text' => ['label' => 'Isi Visi', 'type' => 'textarea', 'max' => 500],
            ],
            self::AboutMission => [
                'title' => ['label' => 'Judul', 'type' => 'input', 'max' => 60],
                'items' => ['label' => 'Poin misi (satu poin per baris)', 'type' => 'lines', 'max' => 1500],
            ],
            self::AboutContact => [
                'eyebrow' => ['label' => 'Teks kecil di atas judul', 'type' => 'input', 'max' => 60],
                'title' => ['label' => 'Judul', 'type' => 'input', 'max' => 150],
                'description' => ['label' => 'Teks', 'type' => 'textarea', 'max' => 500],
                'address' => ['label' => 'Alamat', 'type' => 'textarea', 'max' => 300],
                'phone' => ['label' => 'Telepon / WhatsApp', 'type' => 'input', 'max' => 30],
                'email' => ['label' => 'Email', 'type' => 'input', 'max' => 100, 'rules' => ['email']],
            ],
            self::Branding => [],
        };
    }

    /**
     * Kolom teks tiap kartu (sebelum gambar). Kosong bila section tidak punya kartu.
     *
     * @return array<string, array{label: string, type: string, max: int}>
     */
    public function itemFields(): array
    {
        return match ($this) {
            self::Features => [
                'title' => ['label' => 'Judul', 'type' => 'input', 'max' => 100],
                'description' => ['label' => 'Deskripsi', 'type' => 'textarea', 'max' => 300],
            ],
            default => [],
        };
    }

    public function hasItems(): bool
    {
        return $this->itemFields() !== [];
    }

    /** Kartu section ini dipilih dari daftar Layanan, bukan diketik manual. */
    public function picksServices(): bool
    {
        return $this === self::Services;
    }

    /** Apakah section punya satu gambar utama (bukan per kartu). */
    public function hasImage(): bool
    {
        return match ($this) {
            self::Hero, self::Cta, self::ServiceBanner, self::ServiceCta, self::AboutBanner, self::AboutBackground => true,
            default => false,
        };
    }

    /**
     * Gambar logo yang bisa diganti admin: nama => label, saran ukuran. Kosong bila section bukan pengaturan logo.
     *
     * @return array<string, array{label: string, hint: string, dark?: bool}>
     */
    public function logoFields(): array
    {
        return match ($this) {
            self::Branding => [
                'favicon' => ['label' => 'Ikon tab browser', 'hint' => 'Gambar persegi (mis. 256x256 px). JPG, PNG, atau WEBP, maksimal 2 MB.'],
                'navbar_logo_light' => ['label' => 'Logo navbar di atas hero (versi terang)', 'hint' => 'Tampil saat navbar transparan di atas foto, jadi pakai logo berwarna terang. Maksimal 2 MB.', 'dark' => true],
                'navbar_logo' => ['label' => 'Logo navbar setelah halaman digulir', 'hint' => 'Tampil saat navbar berlatar putih. Maksimal 2 MB.'],
                'footer_logo' => ['label' => 'Logo footer', 'hint' => 'Tampil di dalam kotak putih pada footer. Maksimal 2 MB.'],
            ],
            default => [],
        };
    }
}
