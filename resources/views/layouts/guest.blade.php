<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'LaunchKit Adaptive') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,700,800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="shell flex min-h-screen items-center justify-center px-4 py-10">
            <div class="grid w-full max-w-6xl gap-8 lg:grid-cols-[1fr_460px]">
                <section class="hidden rounded-[36px] border border-white/10 bg-white/5 p-10 text-white shadow-[0_24px_80px_rgba(2,6,23,0.45)] backdrop-blur-xl lg:block">
                    <span class="badge-soft">Adaptive Landing Page Generator</span>
                    <h1 class="mt-6 max-w-xl text-5xl font-black leading-[1.02]">Buat landing page yang berubah sesuai niche bisnis.</h1>
                    <p class="mt-5 max-w-2xl text-lg leading-8 text-slate-300">
                        LaunchKit Adaptive menyusun tone, layout, hero, CTA, warna, dan urutan section secara otomatis berdasarkan niche bisnis Anda.
                    </p>
                    <div class="mt-10 grid gap-4 sm:grid-cols-2">
                        @foreach ([
                            'Hero singkat dan mobile-friendly',
                            'Theme dan layout adaptif per niche',
                            'Editor visual dengan live preview',
                            'Export HTML siap deploy',
                        ] as $point)
                            <div class="rounded-3xl border border-white/10 bg-slate-950/40 p-5 text-sm font-semibold text-slate-200">{{ $point }}</div>
                        @endforeach
                    </div>
                </section>

                <section class="auth-card">
                    <a href="/" class="mb-8 inline-flex items-center gap-3">
                        <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-400 text-sm font-black text-slate-950">LA</span>
                        <span class="text-left">
                            <span class="block text-xs font-bold uppercase tracking-[0.28em] text-emerald-300">LaunchKit</span>
                            <span class="block text-lg font-black text-white">Adaptive</span>
                        </span>
                    </a>
                    {{ $slot }}
                </section>
            </div>
        </div>
    </body>
</html>
