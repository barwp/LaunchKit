@extends('layouts.app', ['title' => 'Checkout - '.$package['name']])

@section('content')
    <section class="mx-auto max-w-4xl panel p-8">
        <div class="border-b border-white/10 pb-5">
            <span class="badge-soft">WhatsApp Checkout</span>
            <h1 class="mt-4 text-3xl font-black text-white">Checkout {{ $package['name'] }}</h1>
        </div>

        <div class="mt-6 grid gap-6 md:grid-cols-2">
            <div class="project-card">
                <p class="text-sm font-bold uppercase tracking-[0.2em] text-slate-400">Ringkasan Paket</p>
                <h2 class="mt-3 text-2xl font-black text-white">{{ $package['name'] }}</h2>
                <p class="mt-3 text-sm leading-7 text-slate-400">{{ $package['description'] }}</p>
            </div>

            <div class="project-card">
                <p class="text-sm font-bold uppercase tracking-[0.2em] text-slate-400">Pembayaran</p>
                <div class="mt-4 space-y-3 text-sm text-slate-300">
                    <div class="flex items-center justify-between"><span>Harga</span><strong>Rp {{ number_format($package['price'], 0, ',', '.') }}</strong></div>
                    <div class="flex items-center justify-between"><span>Diskon Referral</span><strong>Rp {{ number_format($discount, 0, ',', '.') }}</strong></div>
                    <div class="flex items-center justify-between"><span>Referral Dipakai</span><strong>{{ $referralCodeUsed ?: '-' }}</strong></div>
                    <div class="flex items-center justify-between border-t border-white/10 pt-3 text-white"><span>Total Bayar</span><strong>Rp {{ number_format($finalPrice, 0, ',', '.') }}</strong></div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('payment.purchase', $package['slug']) }}" class="mt-8">
            @csrf
            <button type="submit" class="btn-primary w-full">Lanjut Bayar via WhatsApp</button>
        </form>
    </section>
@endsection
