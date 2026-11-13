<?php

namespace App\Http\Requests\Auth;

use App\Services\LoginThrottleService;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Coba login; salah berulang akan mengunci percobaan selama beberapa detik.
     *
     * @throws ValidationException
     */
    public function authenticate(LoginThrottleService $throttle): void
    {
        $email = $this->string('email')->toString();

        $this->ensureIsNotLocked($throttle, $email);

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            $throttle->recordFailure($email, $this->ip());

            throw ValidationException::withMessages([
                'email' => 'Email atau kata sandi tidak sesuai.',
            ]);
        }

        $throttle->clear($email, $this->ip());
    }

    private function ensureIsNotLocked(LoginThrottleService $throttle, string $email): void
    {
        $seconds = $throttle->secondsLeft($email, $this->ip());

        if ($seconds === 0) {
            return;
        }

        event(new Lockout($this));

        throw ValidationException::withMessages([
            'email' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
        ]);
    }
}
