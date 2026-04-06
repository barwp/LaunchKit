<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;

class PaymentService
{
    public function adminWhatsApp(): string
    {
        return (string) config('packages.admin_whatsapp', '628119921200');
    }

    public function generateWhatsAppLink(User $user, array $package, int $discount = 0): string
    {
        $price = (int) ($package['price'] ?? 0);
        $finalPrice = max($price - $discount, 0);
        $referral = $user->referredBy?->referral_code ?: '-';

        $message = implode("\n", [
            'Halo Admin, saya ingin membeli LaunchKit.',
            '',
            'Nama: '.$user->name,
            'Email: '.$user->email,
            'Paket: '.($package['name'] ?? 'Package'),
            'Harga: '.$this->rupiah($price),
            'Diskon: '.$this->rupiah($discount),
            'Total Bayar: '.$this->rupiah($finalPrice),
            'Referral: '.$referral,
            '',
            'Mohon info pembayaran.',
        ]);

        return 'https://wa.me/'.$this->adminWhatsApp().'?text='.rawurlencode($message);
    }

    public function generateWithdrawalWhatsAppLink(User $user, int $balance, int $amount, string $phoneNumber): string
    {
        $message = implode("\n", [
            'Halo Admin, saya ingin melakukan withdrawal.',
            '',
            'Nama: '.$user->name,
            'Email: '.$user->email,
            'Saldo: '.$this->rupiah($balance),
            'Jumlah WD: '.$this->rupiah($amount),
            'No WA: '.$phoneNumber,
            '',
            'Mohon diproses.',
        ]);

        return 'https://wa.me/'.$this->adminWhatsApp().'?text='.rawurlencode($message);
    }

    public function packages(): array
    {
        return config('packages.items', []);
    }

    public function findPackage(string $slug): ?array
    {
        return collect($this->packages())->first(fn (array $package) => ($package['slug'] ?? null) === $slug);
    }

    public function referralDiscountFor(User $user): int
    {
        $firstOrder = ! $user->orders()->exists();

        return $firstOrder && $user->referred_by ? (int) config('packages.referral_discount', 5000) : 0;
    }

    public function rupiah(int $value): string
    {
        return 'Rp '.number_format($value, 0, ',', '.');
    }
}
