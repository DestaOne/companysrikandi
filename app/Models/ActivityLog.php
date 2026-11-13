<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// Sengaja tidak memakai LogsActivity agar pencatatan tidak rekursif.
class ActivityLog extends Model
{
    protected $fillable = ['user_id', 'action', 'subject_type', 'subject_id', 'changes'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['changes' => 'array'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Kalimat singkat aksi, mis. "menambah Gallery". */
    protected function summary(): Attribute
    {
        $verb = ['created' => 'menambah', 'updated' => 'mengubah', 'deleted' => 'menghapus'][$this->action] ?? $this->action;

        return Attribute::get(fn (): string => $verb.' '.class_basename($this->subject_type));
    }
}
