<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    /** Catat waktu masuk; trait LogsActivity di model User mengubahnya menjadi aktivitas "login". */
    public function recordLogin(User $user): void
    {
        $user->update(['last_login_at' => now()]);
    }

    /** Catat waktu keluar; dipanggil sebelum sesi dihapus agar pelakunya masih diketahui. */
    public function recordLogout(User $user): void
    {
        $user->update(['last_logout_at' => now()]);
    }
}
