<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Concerns\LogsActivity;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, LogsActivity, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'last_login_at', 'last_logout_at'];

    protected $hidden = ['password', 'remember_token'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'activities_seen_at' => 'datetime',
            'last_login_at' => 'datetime',
            'last_logout_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /** Huruf awal nama, untuk avatar. */
    protected function initial(): Attribute
    {
        return Attribute::get(fn (): string => mb_strtoupper(mb_substr($this->name, 0, 1)));
    }

    public function activityLabel(): ?string
    {
        return $this->name;
    }

    /** Dari semua perubahan User, hanya kolom waktu masuk/keluar yang menjadi aktivitas. */
    public function activityActionFor(string $event): ?string
    {
        if ($event !== 'updated') {
            return null;
        }

        return match (true) {
            $this->wasChanged('last_login_at') => 'login',
            $this->wasChanged('last_logout_at') => 'logout',
            default => null,
        };
    }
}
