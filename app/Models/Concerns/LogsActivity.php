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

    protected static function logActivity(Model $model, string $action): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'subject_type' => $model::class,
            'subject_id' => $model->getKey(),
            'changes' => $action === 'updated' ? $model->getChanges() : null,
        ]);
    }
}
