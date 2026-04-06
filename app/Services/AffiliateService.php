<?php

namespace App\Services;

use App\Models\AffiliateTransaction;
use App\Models\AffiliateWallet;
use App\Models\Order;
use App\Models\User;
use App\Models\WithdrawalRequest;
use Illuminate\Validation\ValidationException;

class AffiliateService
{
    public function ensureWallet(User $user): AffiliateWallet
    {
        return $user->wallet()->firstOrCreate([], ['balance' => 0]);
    }

    public function calculateCommission(int $finalPrice): int
    {
        return (int) floor($finalPrice * ((int) config('packages.affiliate_percentage', 15) / 100));
    }

    public function recordOrderCommission(Order $order): ?AffiliateTransaction
    {
        $user = $order->user()->with('referredBy')->first();

        if (! $user?->referredBy || $order->final_price <= 0) {
            return null;
        }

        $amount = $this->calculateCommission($order->final_price);
        $wallet = $this->ensureWallet($user->referredBy);
        $wallet->increment('balance', $amount);

        return AffiliateTransaction::query()->create([
            'user_id' => $user->referredBy->id,
            'referred_user_id' => $user->id,
            'amount' => $amount,
            'status' => 'approved',
            'description' => 'Komisi dari pembelian package '.$order->package_name,
        ]);
    }

    public function statsFor(User $user): array
    {
        $wallet = $this->ensureWallet($user);
        $transactions = $user->affiliateTransactions()->with('referredUser')->latest()->get();

        return [
            'balance' => (int) $wallet->balance,
            'total_commission' => (int) $transactions->sum('amount'),
            'referral_count' => $user->referrals()->count(),
            'transactions' => $transactions,
        ];
    }

    public function createWithdrawal(User $user, int $amount, string $phoneNumber): WithdrawalRequest
    {
        $wallet = $this->ensureWallet($user);
        $minimum = (int) config('packages.withdrawal_minimum', 100000);

        if ($amount < $minimum) {
            throw ValidationException::withMessages([
                'amount' => 'Minimal withdrawal adalah '.$minimum.'.',
            ]);
        }

        if ($amount > (int) $wallet->balance) {
            throw ValidationException::withMessages([
                'amount' => 'Jumlah withdrawal melebihi saldo.',
            ]);
        }

        $wallet->decrement('balance', $amount);

        return WithdrawalRequest::query()->create([
            'user_id' => $user->id,
            'amount' => $amount,
            'phone_number' => $phoneNumber,
            'status' => 'pending',
        ]);
    }
}
