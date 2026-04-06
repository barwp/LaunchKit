<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;

class ReferralService
{
    public function generateCode(): string
    {
        do {
            $code = Str::upper(Str::random(3).random_int(100, 999).Str::random(2));
        } while (User::query()->where('referral_code', $code)->exists());

        return $code;
    }

    public function resolveReferrer(?string $code): ?User
    {
        if (! $code) {
            return null;
        }

        return User::query()
            ->where('referral_code', Str::upper(trim($code)))
            ->first();
    }

    public function buildReferralLink(User $user): string
    {
        return url('/register?ref='.$user->referral_code);
    }

    public function ensureReferralCode(User $user): User
    {
        if (! $user->referral_code) {
            $user->forceFill(['referral_code' => $this->generateCode()])->save();
        }

        return $user;
    }
}
