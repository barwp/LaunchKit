<nav x-data="{ open: false }" class="mx-auto mb-8 w-full max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
    <div class="panel flex items-center justify-between px-5 py-4">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-400 text-sm font-black text-slate-950">LA</span>
            <span>
                <span class="block text-xs font-bold uppercase tracking-[0.28em] text-emerald-300">LaunchKit</span>
                <span class="block text-lg font-black text-white">Adaptive</span>
            </span>
        </a>

        <div class="hidden items-center gap-3 sm:flex">
            <a href="{{ route('dashboard') }}" class="btn-secondary">Dashboard</a>
            <a href="{{ route('projects.create') }}" class="btn-primary">Buat Project</a>
            <a href="{{ route('affiliate.index') }}" class="btn-secondary">Affiliate</a>
            @if (auth()->user()?->isAdmin())
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">Users</a>
            @endif
            <a href="{{ route('profile.edit') }}" class="btn-secondary">Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-secondary">Logout</button>
            </form>
        </div>

        <button @click="open = !open" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-white sm:hidden">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h16M4 12h16M4 17h16" />
            </svg>
        </button>
    </div>

    <div x-show="open" x-cloak class="panel mt-3 p-3 sm:hidden">
        <div class="grid gap-2">
            <a href="{{ route('dashboard') }}" class="btn-secondary">Dashboard</a>
            <a href="{{ route('projects.create') }}" class="btn-primary">Buat Project</a>
            <a href="{{ route('affiliate.index') }}" class="btn-secondary">Affiliate</a>
            @if (auth()->user()?->isAdmin())
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">Users</a>
            @endif
            <a href="{{ route('profile.edit') }}" class="btn-secondary">Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-secondary w-full">Logout</button>
            </form>
        </div>
    </div>
</nav>
