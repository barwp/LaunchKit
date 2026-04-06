@extends('layouts.app', ['title' => 'Users - LaunchKit Adaptive'])

@section('content')
    <section class="hero-panel p-8 lg:p-10">
        <div class="glow-orb left-[-50px] top-[-30px] h-40 w-40 bg-emerald-400/20"></div>
        <div class="glow-orb right-[-40px] bottom-[-20px] h-44 w-44 bg-sky-400/10"></div>

        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <span class="badge-soft">Admin Panel</span>
                <h1 class="mt-5 text-4xl font-black leading-tight text-white lg:text-5xl">Kelola akun user dari satu panel admin.</h1>
                <p class="mt-4 max-w-2xl text-lg leading-8 text-slate-300">
                    Lihat status aktivasi user, approval pembayaran, jumlah referral, komisi affiliate, buat akun baru, ubah role admin, ganti password, dan hapus akun.
                </p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn-primary">Tambah User</a>
        </div>
    </section>

    <section class="mt-8 panel p-8">
        <div class="border-b border-white/10 pb-6">
            <p class="text-sm font-bold uppercase tracking-[0.24em] text-slate-400">Pending Approval</p>
            <h2 class="mt-2 text-2xl font-black text-white">User yang menunggu konfirmasi pembayaran</h2>
        </div>

        <div class="mt-6 grid gap-4">
            @forelse ($pendingUsers as $user)
                @php($pendingOrder = $user->orders->first())
                <article class="project-card">
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="preview-chip">Pending</span>
                                <span class="preview-chip">{{ $pendingOrder?->package_name ?? 'LaunchKit Starter' }}</span>
                                <span class="preview-chip">{{ $pendingOrder ? ('Rp '.number_format($pendingOrder->final_price, 0, ',', '.')) : 'Rp 99.000' }}</span>
                                @if ($user->referredBy)
                                    <span class="preview-chip">Referral {{ $user->referredBy->referral_code }}</span>
                                @endif
                            </div>
                            <h2 class="mt-4 text-2xl font-black text-white">{{ $user->name }}</h2>
                            <p class="mt-2 text-sm text-slate-400">{{ $user->email }}</p>
                            <p class="mt-3 text-sm leading-7 text-slate-400">
                                User ini baru mendaftar dan diarahkan ke WhatsApp untuk pembayaran paket permanen
                                <span class="font-semibold text-white">LaunchKit Starter</span>.
                                Setelah pembayaran terkonfirmasi, admin bisa mengaktifkan akun dari panel ini.
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <form method="POST" action="{{ route('admin.users.approve', $user) }}">
                                @csrf
                                <button type="submit" class="btn-primary">ACC</button>
                            </form>
                            <form method="POST" action="{{ route('admin.users.reject', $user) }}">
                                @csrf
                                <button type="submit" class="btn-secondary">Tolak</button>
                            </form>
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn-secondary">Detail</a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-[28px] border border-dashed border-white/10 bg-slate-950/40 px-6 py-10 text-center text-slate-400">
                    Tidak ada user yang menunggu approval.
                </div>
            @endforelse
        </div>

        @if ($pendingUsers->hasPages())
            <div class="mt-6">
                {{ $pendingUsers->links() }}
            </div>
        @endif
    </section>

    <section class="mt-8 panel p-8">
        <div class="border-b border-white/10 pb-6">
            <p class="text-sm font-bold uppercase tracking-[0.24em] text-slate-400">User & Affiliate</p>
            <h2 class="mt-2 text-2xl font-black text-white">Semua user, referral, dan komisinya</h2>
        </div>

        <div class="grid gap-5">
            @foreach ($users as $user)
                <article class="project-card">
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="preview-chip">{{ $user->is_admin ? 'Admin' : 'User' }}</span>
                                <span class="preview-chip">{{ strtoupper($user->account_status) }}</span>
                                <span class="preview-chip">{{ $user->projects_count }} Project</span>
                                <span class="preview-chip">{{ $user->referrals_count }} Referral</span>
                                <span class="preview-chip">Komisi Rp {{ number_format((int) ($user->affiliate_transactions_sum_amount ?? 0), 0, ',', '.') }}</span>
                                <span class="preview-chip">{{ $user->created_at?->format('d M Y') }}</span>
                            </div>
                            <h2 class="mt-4 text-2xl font-black text-white">{{ $user->name }}</h2>
                            <p class="mt-2 text-sm text-slate-400">{{ $user->email }}</p>
                            <div class="mt-4 grid gap-3 sm:grid-cols-3">
                                <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                                    <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-slate-500">Referral Code</p>
                                    <p class="mt-2 text-base font-black text-white">{{ $user->referral_code ?? '-' }}</p>
                                </div>
                                <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                                    <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-slate-500">Orang Diajak</p>
                                    <p class="mt-2 text-base font-black text-white">{{ $user->referrals_count }}</p>
                                </div>
                                <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                                    <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-slate-500">Total Komisi</p>
                                    <p class="mt-2 text-base font-black text-white">Rp {{ number_format((int) ($user->affiliate_transactions_sum_amount ?? 0), 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            @if ($user->account_status === 'pending_payment')
                                <form method="POST" action="{{ route('admin.users.approve', $user) }}">
                                    @csrf
                                    <button type="submit" class="btn-primary">ACC</button>
                                </form>
                                <form method="POST" action="{{ route('admin.users.reject', $user) }}">
                                    @csrf
                                    <button type="submit" class="btn-secondary">Tolak</button>
                                </form>
                            @endif
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn-secondary">Kelola</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        @if ($users->hasPages())
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        @endif
    </section>
@endsection
