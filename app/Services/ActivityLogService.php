<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ActivityLogService
{
    public function paginate(int $perPage = 20): LengthAwarePaginator
    {
        return ActivityLog::with('user')->orderByDesc('created_at')->orderByDesc('id')->paginate($perPage);
    }

    /** Jumlah aktivitas sejak admin terakhir membuka menu Aktivitas. */
    public function newCount(User $user): int
    {
        return $this->unseen($user)->count();
    }

    /**
     * Aktivitas baru terbaru untuk pratinjau di lonceng.
     *
     * @return Collection<int, ActivityLog>
     */
    public function newest(User $user, int $limit = 5): Collection
    {
        return $this->unseen($user)->with('user')->orderByDesc('created_at')->orderByDesc('id')->limit($limit)->get();
    }

    /**
     * Masuk/keluar tidak dihitung karena hanya menambah gangguan. Pesan masuk juga tidak,
     * karena sudah punya notifikasi sendiri (pesan belum dibaca) agar tidak terhitung dua kali.
     *
     * @return Builder<ActivityLog>
     */
    private function unseen(User $user): Builder
    {
        return ActivityLog::query()
            ->whereNotIn('action', ['login', 'logout'])
            ->whereNot(fn (Builder $query) => $query->where('action', 'created')->where('subject_type', ContactMessage::class))
            ->when($user->activities_seen_at, fn (Builder $query, $seenAt) => $query->where('created_at', '>', $seenAt));
    }

    public function markSeen(User $user): void
    {
        $user->forceFill(['activities_seen_at' => now()])->save();
    }
}
