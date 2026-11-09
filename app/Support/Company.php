<?php

namespace App\Support;

/** Tautan kontak perusahaan yang dibentuk dari config/company.php. */
class Company
{
    /** Tautan tel: hanya dibuat bila nomor berisi minimal 8 digit (placeholder tidak dihitung). */
    public static function phoneHref(): ?string
    {
        $phone = (string) config('company.phone');

        if (strlen(preg_replace('/\D/', '', $phone)) < 8) {
            return null;
        }

        return 'tel:'.preg_replace('/[^\d+]/', '', $phone);
    }

    public static function emailHref(): ?string
    {
        $email = (string) config('company.email');

        return filter_var($email, FILTER_VALIDATE_EMAIL) ? 'mailto:'.$email : null;
    }
}
