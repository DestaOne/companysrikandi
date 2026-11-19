<?php

namespace App\Enums;

enum ServiceStatus: string
{
    case Published = 'published';
    case Draft = 'draft';

    public function label(): string
    {
        return match ($this) {
            self::Published => 'Terbit',
            self::Draft => 'Draft',
        };
    }

    /**
     * Pilihan untuk kolom form: nilai => teks.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $status) {
            $options[$status->value] = $status->label();
        }

        return $options;
    }
}
