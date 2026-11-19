<?php

namespace App\Listeners;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

/** Mencatat masuk dan keluar admin. Event auth bukan event model, jadi tidak bisa lewat trait LogsActivity. */
class LogAuthActivity
{
    public function handleLogin(Login $event): void
    {
        $this->record($event->user, 'login');
    }

    public function handleLogout(Logout $event): void
    {
        // Logout tanpa pengguna (sesi sudah kedaluwarsa) tidak perlu dicatat
        if ($event->user) {
            $this->record($event->user, 'logout');
        }
    }

    private function record(User $user, string $action): void
    {
        ActivityLog::create([
            'user_id' => $user->getKey(),
            'action' => $action,
            'subject_type' => User::class,
            'subject_id' => $user->getKey(),
            'subject_label' => $user->name,
        ]);
    }
}
