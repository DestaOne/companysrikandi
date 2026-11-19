<?php

namespace App\Models;

use App\Enums\HomeSectionKey;
use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class HomeSection extends Model
{
    use LogsActivity;

    protected $fillable = ['section', 'content'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['content' => 'array'];
    }

    public function activityLabel(): ?string
    {
        $key = HomeSectionKey::tryFrom($this->section);

        return $key ? $key->label().' ('.$key->pageLabel().')' : $this->section;
    }
}
