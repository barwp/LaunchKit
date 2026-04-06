@extends('layouts.app', ['title' => 'Tambah User - LaunchKit Adaptive'])

@section('content')
    <section class="mx-auto max-w-3xl panel p-8">
        <div class="border-b border-white/10 pb-5">
            <span class="badge-soft">Admin Create User</span>
            <h1 class="mt-4 text-3xl font-black text-white">Buat akun user baru</h1>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}" class="mt-6 space-y-5">
            @csrf

            <div>
                <label class="field-label" for="name">Nama</label>
                <input id="name" name="name" class="field-input" value="{{ old('name') }}" required>
            </div>

            <div>
                <label class="field-label" for="email">Email</label>
                <input id="email" name="email" type="email" class="field-input" value="{{ old('email') }}" required>
            </div>

            <div>
                <label class="field-label" for="password">Password</label>
                <input id="password" name="password" type="password" class="field-input" required>
            </div>

            <div>
                <label class="field-label" for="password_confirmation">Konfirmasi Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="field-input" required>
            </div>

            <label class="flex items-center gap-3 rounded-2xl border border-white/10 bg-slate-950/40 px-4 py-4 text-sm text-slate-200">
                <input type="checkbox" name="is_admin" value="1" class="h-4 w-4 rounded border-white/20 bg-slate-950 text-emerald-400 focus:ring-emerald-400" @checked(old('is_admin'))>
                Jadikan akun ini sebagai admin
            </label>

            <div class="flex gap-3 border-t border-white/10 pt-5">
                <button type="submit" class="btn-primary">Simpan User</button>
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">Kembali</a>
            </div>
        </form>
    </section>
@endsection
