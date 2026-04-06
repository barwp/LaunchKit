<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'name', 'niche', 'business_type', 'raw_input', 'generated_data', 'edited_data'])]
class Project extends Model
{
    protected function casts(): array
    {
        return [
            'raw_input' => 'array',
            'generated_data' => 'array',
            'edited_data' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resolvedData(): array
    {
        return $this->edited_data ?: $this->generated_data ?: [];
    }
}
