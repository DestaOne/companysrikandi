<?php

namespace App\Models\Concerns;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(fn (Model $model) => static::logActivity($model, 'created'));
        static::updated(fn (Model $model) => static::logActivity($model, 'updated'));
        static::deleted(fn (Model $model) => static::logActivity($model, 'deleted'));
    }

    protected static function logActivity(Model $model, string $event): void
    {
        $action = $model->activityActionFor($event);

        if ($action === null) {
            return;
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'subject_type' => $model::class,
            'subject_id' => $model->getKey(),
            'subject_label' => $model->activityLabel(),
            'changes' => $action === 'updated' ? $model->getChanges() : null,
        ]);
    }

    /**
     * Nama aksi yang dicatat untuk event model (created/updated/deleted); null berarti tidak dicatat.
     * Model boleh menimpanya, mis. User mengubah pembaruan kolom waktu masuk menjadi "login".
     */
    public function activityActionFor(string $event): ?string
    {
        return $event;
    }

    /** Nama data untuk riwayat aktivitas (mis. judul). Model boleh menimpanya. */
    public function activityLabel(): ?string
    {
        return null;
    }
}
