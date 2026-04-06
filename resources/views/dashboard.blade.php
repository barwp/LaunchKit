@extends('layouts.app', ['title' => 'Dashboard - LaunchKit Adaptive'])

@section('content')
    <section class="hero-panel p-8 lg:p-10">
        <div class="glow-orb left-[-60px] top-[-40px] h-44 w-44 bg-emerald-400/20"></div>
        <div class="glow-orb right-[-50px] top-[30%] h-52 w-52 bg-sky-400/10"></div>

        <div class="relative z-10 grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
            <div>
                <span class="badge-soft">Adaptive Niche Engine</span>
                <h1 class="mt-6 max-w-3xl text-4xl font-black leading-tight text-white lg:text-6xl">
                    Landing page yang berubah feel, struktur, dan visual sesuai niche.
                </h1>
                <p class="mt-5 max-w-2xl text-lg leading-8 text-slate-300">
                    Bukan sekadar ganti warna. LaunchKit Adaptive mengubah hero, copy tone, section order, CTA behavior, dan gaya visual supaya lebih cocok dengan bisnis yang dipilih.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('projects.create') }}" class="btn-primary">Buat Project Baru</a>
                    <a href="{{ route('affiliate.index') }}" class="btn-secondary">Buka Affiliate</a>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                @foreach ([
                    ['Adaptive Hero', 'Headline singkat, tajam, dan mobile-friendly.'],
                    ['Niche Preset', 'Crypto, luxury, beauty, sampai automotive feel.'],
                    ['Visual Editor', 'Edit teks, style, urutan section, dan gambar.'],
                    ['Static Export', 'HTML bersih siap upload ke hosting.'],
                ] as [$title, $copy])
                    <article class="metric-card">
                        <p class="text-sm font-black uppercase tracking-[0.22em] text-emerald-300">{{ $title }}</p>
                        <p class="mt-3 text-sm leading-7 text-slate-300">{{ $copy }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mt-8 grid gap-6 lg:grid-cols-4">
        @foreach ([
            ['01', 'Brief bisnis', 'User isi niche, value, tone, dan asset visual.'],
            ['02', 'Adaptive generate', 'Engine memilih theme, hero, layout, dan section.'],
            ['03', 'Visual edit', 'Semua copy dan gambar bisa diubah langsung.'],
            ['04', 'Export HTML', 'Landing page final siap dibagikan atau di-deploy.'],
        ] as [$number, $title, $copy])
            <article class="panel p-5">
                <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-400 text-sm font-black text-slate-950">{{ $number }}</div>
                <h2 class="mt-5 text-lg font-black text-white">{{ $title }}</h2>
                <p class="mt-3 text-sm leading-7 text-slate-400">{{ $copy }}</p>
            </article>
        @endforeach
    </section>

    <section class="mt-8">
        <div class="panel p-8">
            <p class="text-sm font-bold uppercase tracking-[0.24em] text-slate-400">Affiliate</p>
            <h2 class="mt-3 text-2xl font-black text-white">Referral code, link, dan status akun Anda</h2>
            <div class="mt-6 grid gap-4 md:grid-cols-3">
                <div class="metric-card">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Referral Code</p>
                    <p class="mt-3 text-2xl font-black text-white">{{ $referralCode }}</p>
                </div>
                <div class="metric-card">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Latest Order</p>
                    <p class="mt-3 text-xl font-black text-white">{{ $latestOrder?->package_name ?? 'Belum ada order' }}</p>
                    <p class="mt-2 text-sm text-slate-400">{{ $latestOrder ? ('Status: '.$latestOrder->status) : 'Transaksi dilakukan saat proses registrasi akun.' }}</p>
                </div>
                <div class="metric-card">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Pembelian Paket</p>
                    <p class="mt-3 text-xl font-black text-white">Di luar akun</p>
                    <p class="mt-2 text-sm text-slate-400">Order paket LaunchKit Starter dilakukan saat register dan diarahkan ke WhatsApp admin.</p>
                </div>
            </div>

            <div class="mt-5 rounded-[24px] border border-white/10 bg-slate-950/40 p-4">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Referral Link</p>
                <div class="mt-3 flex flex-col gap-3 md:flex-row md:items-center">
                    <input id="referral-link" readonly class="field-input" value="{{ $referralLink }}">
                    <button type="button" class="btn-primary" onclick="navigator.clipboard.writeText(document.getElementById('referral-link').value)">Copy Link</button>
                </div>
            </div>
        </div>
    </section>

    <section id="project-list" class="mt-8 panel p-8">
        <div class="flex flex-col gap-3 border-b border-white/10 pb-6 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm font-bold uppercase tracking-[0.24em] text-slate-400">Dashboard</p>
                <h2 class="mt-2 text-2xl font-black text-white">Project Adaptive Landing Page</h2>
            </div>
            <a href="{{ route('projects.create') }}" class="btn-primary">Buat Project Baru</a>
        </div>

        <div class="mt-6 grid gap-5">
            @forelse ($projects as $project)
                @php($theme = data_get($project->resolvedData(), 'theme', []))
                @php($palette = data_get($theme, 'palette', []))
                <article class="project-card">
                    <div class="absolute inset-x-0 top-0 h-1 rounded-t-[28px]"
                        style="background: linear-gradient(90deg, {{ $palette['primary'] ?? '#10b981' }}, {{ $palette['accent'] ?? '#3b82f6' }});"></div>

                    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="preview-chip">{{ $nicheLabels[$project->niche] ?? ucfirst($project->niche) }}</span>
                                <span class="preview-chip">{{ ucfirst($project->business_type) }}</span>
                                @if (data_get($theme, 'visual_preset_key'))
                                    <span class="preview-chip">{{ str(data_get($theme, 'visual_preset_key'))->headline() }}</span>
                                @endif
                            </div>
                            <h3 class="mt-4 text-2xl font-black text-white">{{ $project->name }}</h3>
                            <p class="mt-2 text-sm text-slate-400">
                                Dibuat {{ $project->created_at?->format('d M Y H:i') }} · Hero style: {{ data_get($theme, 'hero_style', 'adaptive') }}
                            </p>

                            <div class="mt-5 flex gap-2">
                                @foreach (['primary', 'accent', 'background'] as $swatch)
                                    <span class="h-9 w-9 rounded-2xl border border-white/10"
                                        style="background: {{ $palette[$swatch] ?? '#111827' }}"></span>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('projects.edit', $project) }}" class="btn-secondary">Edit</a>
                            <a href="{{ route('projects.export', $project) }}" class="btn-secondary">Export</a>
                            <form method="POST" action="{{ route('projects.duplicate', $project) }}">
                                @csrf
                                <button type="submit" class="btn-primary">Duplicate</button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-[28px] border border-dashed border-white/10 bg-slate-950/40 px-6 py-12 text-center">
                    <h3 class="text-xl font-black text-white">Belum ada project</h3>
                    <p class="mt-3 text-sm text-slate-400">Mulai dengan satu project baru, lalu pilih niche bisnis untuk melihat adaptive engine bekerja.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
