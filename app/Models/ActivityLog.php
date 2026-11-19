<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Sengaja tidak memakai LogsActivity agar pencatatan tidak rekursif.
class ActivityLog extends Model
{
    private const VERBS = ['created' => 'menambah', 'updated' => 'mengubah', 'deleted' => 'menghapus'];

    /** Sebutan jenis data di riwayat aktivitas. */
    private const SUBJECTS = [
        Gallery::class => 'gambar galeri',
        Service::class => 'layanan',
        HomeSection::class => 'bagian halaman',
        ContactMessage::class => 'pesan masuk',
    ];

    private const FIELDS = [
        'title' => 'Judul', 'description' => 'Deskripsi', 'image' => 'Gambar', 'status' => 'Status',
        'sort_order' => 'Urutan', 'products' => 'Barang', 'slug' => 'Alamat halaman', 'content' => 'Isi',
        'read_at' => 'Status dibaca',
    ];

    protected $fillable = ['user_id', 'action', 'subject_type', 'subject_id', 'subject_label', 'changes'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['changes' => 'array'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Kalimat singkat tanpa nama pelaku, mis. "menambah layanan “Mesin”". */
    protected function summary(): Attribute
    {
        return Attribute::get(function (): string {
            $label = $this->subject_label ? ' “'.$this->subject_label.'”' : '';

            if (in_array($this->action, ['login', 'logout'], true)) {
                return $this->action === 'login' ? 'masuk ke panel admin' : 'keluar dari panel admin';
            }

            if ($this->subject_type === ContactMessage::class && $this->action === 'created') {
                return 'menerima pesan masuk dari'.$label;
            }

            $verb = self::VERBS[$this->action] ?? $this->action;
            $subject = self::SUBJECTS[$this->subject_type] ?? class_basename($this->subject_type);

            return $verb.' '.$subject.$label;
        });
    }

    /** Halaman/menu admin tempat data ini dikelola, mis. "Galeri" atau "Tentang". Kosong untuk masuk/keluar. */
    protected function page(): Attribute
    {
        return Attribute::get(function (): ?string {
            return match ($this->subject_type) {
                Gallery::class => 'Galeri',
                Service::class => 'Layanan',
                ContactMessage::class => 'Pesan masuk',
                // Nama bagian halaman berbentuk "Hero Section (Tentang)": halamannya ada di dalam kurung
                HomeSection::class => preg_match('/\((.+)\)$/', (string) $this->subject_label, $match) ? $match[1] : null,
                default => null,
            };
        });
    }

    /** Waktu aktivitas dalam zona waktu perusahaan (database menyimpan UTC). */
    protected function occurredAt(): Attribute
    {
        return Attribute::get(fn (): CarbonInterface => $this->created_at->copy()->timezone(config('company.timezone')));
    }

    /** Nama kolom yang berubah (untuk aktivitas "mengubah"), mis. "Judul, Gambar". Kosong bila tidak ada rinciannya. */
    protected function changedFields(): Attribute
    {
        return Attribute::get(function (): string {
            $names = collect(array_keys($this->getAttribute('changes') ?? []) /* bukan $this->changes: itu properti internal Eloquent */)
                ->reject(fn (string $field): bool => $field === 'updated_at')
                ->map(fn (string $field): string => self::FIELDS[$field] ?? $field)
                ->unique();

            return $names->implode(', ');
        });
    }
}
