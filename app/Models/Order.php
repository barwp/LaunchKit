<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'package_name', 'price', 'discount', 'final_price', 'referral_code_used', 'status'])]
class Order extends Model
{
    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'discount' => 'integer',
            'final_price' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
