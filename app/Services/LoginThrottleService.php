<?php

namespace App\Services;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginThrottleService
{
    /** Jendela hitung salah login (detik); lewat dari ini hitungan dianggap kedaluwarsa. */
    private const ATTEMPT_WINDOW = 300;

    /**
     * Sisa detik penguncian; 0 bila login boleh dicoba.
     */
    public function secondsLeft(string $email, string $ip): int
    {
        $key = $this->key($email, $ip);

        return RateLimiter::tooManyAttempts($this->lockKey($key), 1)
            ? RateLimiter::availableIn($this->lockKey($key))
            : 0;
    }

    /**
     * Catat satu kali gagal login; kunci tombol bila sudah mencapai batas.
     */
    public function recordFailure(string $email, string $ip): void
    {
        $key = $this->key($email, $ip);

        RateLimiter::hit($key, self::ATTEMPT_WINDOW);

        if (RateLimiter::attempts($key) < config('auth.login_throttle.max_attempts')) {
            return;
        }

        RateLimiter::clear($key);
        RateLimiter::hit($this->lockKey($key), config('auth.login_throttle.lockout_seconds'));
    }

    public function clear(string $email, string $ip): void
    {
        $key = $this->key($email, $ip);

        RateLimiter::clear($key);
        RateLimiter::clear($this->lockKey($key));
    }

    private function key(string $email, string $ip): string
    {
        return Str::transliterate(Str::lower($email).'|'.$ip);
    }

    private function lockKey(string $key): string
    {
        return $key.':lock';
    }
}
