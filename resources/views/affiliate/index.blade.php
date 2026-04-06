@extends('layouts.app', ['title' => 'Affiliate - LaunchKit Adaptive'])

@section('content')
    <section class="hero-panel p-8 lg:p-10">
        <div class="glow-orb left-[-60px] top-[-30px] h-44 w-44 bg-emerald-400/20"></div>
        <div class="glow-orb right-[-30px] bottom-[-20px] h-40 w-40 bg-sky-400/10"></div>
        <div class="relative z-10">
            <span class="badge-soft">Affiliate Center</span>
            <h1 class="mt-5 text-4xl font-black text-white lg:text-5xl">Kelola referral, komisi, dan withdrawal.</h1>
            <p class="mt-4 max-w-2xl text-lg leading-8 text-slate-300">Sistem affiliate berjalan tanpa payment gateway. Order diarahkan ke WhatsApp admin, lalu komisi dicatat ke wallet affiliate.</p>
        </div>
    </section>

    <section class="mt-8 grid gap-6 lg:grid-cols-4">
        <article class="panel p-6"><p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Saldo</p><p class="mt-3 text-3xl font-black text-white">Rp {{ number_format($stats['balance'], 0, ',', '.') }}</p></article>
        <article class="panel p-6"><p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Total Komisi</p><p class="mt-3 text-3xl font-black text-white">Rp {{ number_format($stats['total_commission'], 0, ',', '.') }}</p></article>
        <article class="panel p-6"><p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Jumlah Referral</p><p class="mt-3 text-3xl font-black text-white">{{ $stats['referral_count'] }}</p></article>
        <article class="panel p-6"><p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Min WD</p><p class="mt-3 text-3xl font-black text-white">Rp {{ number_format($withdrawalMinimum, 0, ',', '.') }}</p></article>
    </section>

    <section class="mt-8 grid gap-6 lg:grid-cols-[1fr_420px]">
        <div class="panel p-8">
            <p class="text-sm font-bold uppercase tracking-[0.24em] text-slate-400">Referral Link</p>
            <div class="mt-4 rounded-[24px] border border-white/10 bg-slate-950/40 p-4">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Referral Code</p>
                <p class="mt-2 text-2xl font-black text-white">{{ $user->referral_code }}</p>
                <input id="affiliate-ref-link" readonly class="field-input mt-4" value="{{ $referralLink }}">
                <button type="button" class="btn-primary mt-4" onclick="navigator.clipboard.writeText(document.getElementById('affiliate-ref-link').value)">Copy Referral Link</button>
            </div>

            <div class="mt-8">
                <p class="text-sm font-bold uppercase tracking-[0.24em] text-slate-400">History Affiliate</p>
                <div class="mt-4 grid gap-4">
                    @forelse ($stats['transactions'] as $transaction)
                        <article class="project-card">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-lg font-black text-white">{{ $transaction->referredUser?->name ?? 'Referral User' }}</p>
                                    <p class="mt-1 text-sm text-slate-400">{{ $transaction->description }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-black text-emerald-300">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
                                    <p class="mt-1 text-xs uppercase tracking-[0.18em] text-slate-500">{{ $transaction->status }}</p>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-[28px] border border-dashed border-white/10 bg-slate-950/40 px-6 py-10 text-center text-slate-400">
                            Belum ada transaksi affiliate.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <aside class="panel h-fit p-8">
            <p class="text-sm font-bold uppercase tracking-[0.24em] text-slate-400">Withdrawal</p>
            <h2 class="mt-3 text-2xl font-black text-white">Ajukan withdrawal via WhatsApp admin</h2>
            <form method="POST" action="{{ route('affiliate.withdraw') }}" class="mt-6 space-y-5">
                @csrf
                <div>
                    <label class="field-label" for="amount">Jumlah WD</label>
                    <input id="amount" name="amount" type="number" min="{{ $withdrawalMinimum }}" max="{{ $stats['balance'] }}" class="field-input" value="{{ old('amount', $withdrawalMinimum) }}">
                </div>
                <div>
                    <label class="field-label" for="phone_number">No WhatsApp Penerima</label>
                    <input id="phone_number" name="phone_number" class="field-input" value="{{ old('phone_number') }}" placeholder="08xxxxxxxxxx">
                </div>
                <button type="submit" class="btn-primary w-full" @disabled($stats['balance'] < $withdrawalMinimum)>
                    {{ $stats['balance'] < $withdrawalMinimum ? 'Saldo belum cukup untuk WD' : 'Ajukan Withdrawal' }}
                </button>
            </form>
        </aside>
    </section>
@endsection
