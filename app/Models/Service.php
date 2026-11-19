<?php

namespace App\Models;

use App\Enums\ServiceStatus;
use App\Models\Concerns\LogsActivity;
use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    /** @use HasFactory<ServiceFactory> */
    use HasFactory, LogsActivity;

    protected $fillable = ['slug', 'title', 'description', 'image', 'products', 'status', 'sort_order'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['products' => 'array', 'status' => ServiceStatus::class];
    }

    /**
     * @param  Builder<Service>  $query
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('status', ServiceStatus::Published);
    }

    /**
     * @param  Builder<Service>  $query
     */
    public function scopeDraft(Builder $query): void
    {
        $query->where('status', ServiceStatus::Draft);
    }

    /**
     * Urutan tampil yang diatur admin lewat seret-dan-lepas; id sebagai pembeda bila sama.
     *
     * @param  Builder<Service>  $query
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }

    /** Daftar barang sebagai teks satu baris per barang, untuk kolom form. */
    protected function productsText(): Attribute
    {
        return Attribute::get(fn (): string => implode("\n", $this->products ?? []));
    }

    public function activityLabel(): ?string
    {
        return $this->title;
    }
}
