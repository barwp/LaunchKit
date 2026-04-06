@extends('layouts.app', ['title' => 'Kelola User - '.$managedUser->name])

@section('content')
    <section class="grid gap-6 xl:grid-cols-[1fr_380px]">
        <div class="panel p-8">
            <div class="border-b border-white/10 pb-5">
                <span class="badge-soft">Admin User Detail</span>
                <h1 class="mt-4 text-3xl font-black text-white">{{ $managedUser->name }}</h1>
                <p class="mt-3 text-sm text-slate-400">{{ $managedUser->email }}</p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="preview-chip">{{ $managedUser->is_admin ? 'Admin' : 'User' }}</span>
                    <span class="preview-chip">{{ strtoupper($managedUser->account_status) }}</span>
                    <span class="preview-chip">{{ $managedUser->referrals_count }} Referral</span>
                    <span class="preview-chip">Komisi Rp {{ number_format((int) ($managedUser->affiliate_transactions_sum_amount ?? 0), 0, ',', '.') }}</span>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.users.update', $managedUser) }}" class="mt-6 space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="field-label" for="name">Nama</label>
                    <input id="name" name="name" class="field-input" value="{{ old('name', $managedUser->name) }}" required>
                </div>

                <div>
                    <label class="field-label" for="email">Email</label>
                    <input id="email" name="email" type="email" class="field-input" value="{{ old('email', $managedUser->email) }}" required>
                </div>

                <label class="flex items-center gap-3 rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-4 text-sm text-slate-200">
                    <input type="checkbox" name="is_admin" value="1" class="h-4 w-4 rounded border-white/20 bg-slate-950 text-emerald-400 focus:ring-emerald-400" @checked(old('is_admin', $managedUser->is_admin))>
                    Beri akses admin
                </label>

                <div>
                    <label class="field-label" for="account_status">Status Akun</label>
                    <select id="account_status" name="account_status" class="field-input">
                        <option value="pending_payment" @selected(old('account_status', $managedUser->account_status) === 'pending_payment')>Pending Payment</option>
                        <option value="approved" @selected(old('account_status', $managedUser->account_status) === 'approved')>Approved</option>
                        <option value="rejected" @selected(old('account_status', $managedUser->account_status) === 'rejected')>Rejected</option>
                    </select>
                </div>

                <div class="flex gap-3 border-t border-white/10 pt-5">
                    <button type="submit" class="btn-primary">Update User</button>
                    <a href="{{ route('admin.users.index') }}" class="btn-secondary">Kembali</a>
                </div>
            </form>

            @if ($managedUser->account_status === 'pending_payment')
                <div class="mt-8 border-t border-white/10 pt-6">
                    <p class="text-sm font-bold uppercase tracking-[0.24em] text-slate-400">Approval Pembayaran</p>
                    <p class="mt-3 text-sm leading-7 text-slate-400">
                        Jika pembayaran user sudah masuk melalui WhatsApp admin, aktifkan akun agar user bisa login dan memakai LaunchKit Adaptive.
                    </p>
                    <div class="mt-5 flex flex-wrap gap-3">
                        <form method="POST" action="{{ route('admin.users.approve', $managedUser) }}">
                            @csrf
                            <button type="submit" class="btn-primary">ACC User</button>
                        </form>
                        <form method="POST" action="{{ route('admin.users.reject', $managedUser) }}">
                            @csrf
                            <button type="submit" class="btn-secondary">Tolak User</button>
                        </form>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.users.password', $managedUser) }}" class="mt-8 border-t border-white/10 pt-6 space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="field-label" for="password">Password Baru</label>
                    <input id="password" name="password" type="password" class="field-input" required>
                </div>

                <div>
                    <label class="field-label" for="password_confirmation">Konfirmasi Password Baru</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="field-input" required>
                </div>

                <button type="submit" class="btn-secondary">Ganti Password User</button>
            </form>
        </div>

        <aside class="panel h-fit p-6">
            <p class="text-sm font-bold uppercase tracking-[0.24em] text-slate-400">Ringkasan User</p>
            <div class="mt-5 space-y-4">
                <div class="metric-card">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Role</p>
                    <p class="mt-2 text-xl font-black text-white">{{ $managedUser->is_admin ? 'Admin' : 'User' }}</p>
                </div>
                <div class="metric-card">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Status Akun</p>
                    <p class="mt-2 text-xl font-black text-white">{{ strtoupper($managedUser->account_status) }}</p>
                </div>
                <div class="metric-card">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Referral Code</p>
                    <p class="mt-2 text-xl font-black text-white">{{ $managedUser->referral_code ?? '-' }}</p>
                </div>
                <div class="metric-card">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Jumlah Project</p>
                    <p class="mt-2 text-xl font-black text-white">{{ $managedUser->projects_count }}</p>
                </div>
                <div class="metric-card">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Jumlah Referral</p>
                    <p class="mt-2 text-xl font-black text-white">{{ $managedUser->referrals_count }}</p>
                </div>
                <div class="metric-card">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Total Komisi</p>
                    <p class="mt-2 text-xl font-black text-white">Rp {{ number_format((int) ($managedUser->affiliate_transactions_sum_amount ?? 0), 0, ',', '.') }}</p>
                </div>
                <div class="metric-card">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Wallet Affiliate</p>
                    <p class="mt-2 text-xl font-black text-white">Rp {{ number_format((int) ($managedUser->wallet?->balance ?? 0), 0, ',', '.') }}</p>
                </div>
                <div class="metric-card">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Dibuat</p>
                    <p class="mt-2 text-xl font-black text-white">{{ $managedUser->created_at?->format('d M Y H:i') }}</p>
                </div>
            </div>

            @if ($managedUser->orders->isNotEmpty())
                <div class="mt-6 border-t border-white/10 pt-6">
                    <p class="text-sm font-bold uppercase tracking-[0.24em] text-slate-400">Order User</p>
                    <div class="mt-4 space-y-3">
                        @foreach ($managedUser->orders as $order)
                            <div class="rounded-2xl border border-white/10 bg-slate-950/40 p-4">
                                <p class="text-sm font-black text-white">{{ $order->package_name }}</p>
                                <p class="mt-1 text-sm text-slate-400">Status: {{ strtoupper($order->status) }}</p>
                                <p class="mt-1 text-sm text-slate-400">Final: Rp {{ number_format($order->final_price, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.users.destroy', $managedUser) }}" class="mt-6 border-t border-white/10 pt-6" onsubmit="return confirm('Hapus user ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full rounded-2xl border border-rose-400/20 bg-rose-400/10 px-5 py-3 text-sm font-bold text-rose-200 transition hover:bg-rose-400/20">
                    Hapus User
                </button>
            </form>
        </aside>
    </section>
@endsection
