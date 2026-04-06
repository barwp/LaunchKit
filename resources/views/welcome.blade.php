<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Launchkit by novadigital.id</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="shell min-h-screen">
        <div class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <header class="panel flex flex-col gap-4 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.28em] text-emerald-300">Launchkit by novadigital.id</p>
                    <p class="mt-2 text-sm text-slate-400">Website builder simpel untuk jualan produk digital dan jasa tanpa ribet coding.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-primary">Masuk Dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="btn-primary">Daftar & Order</a>
                        <a href="{{ route('login') }}" class="btn-secondary">Login</a>
                    @endauth
                </div>
            </header>

            <section class="mt-6 grid gap-6 lg:grid-cols-[1.08fr_0.92fr]">
                <div class="hero-panel p-7 sm:p-9 lg:p-12">
                    <div class="glow-orb left-[-80px] top-[-50px] h-44 w-44 bg-emerald-400/20"></div>
                    <div class="glow-orb right-[-20px] top-[18%] h-48 w-48 bg-sky-400/12"></div>
                    <div class="relative z-10">
                        <span class="badge-soft">One-time access • Rp 99.000</span>
                        <h1 class="mt-6 max-w-4xl text-4xl font-black leading-[0.98] text-white sm:text-5xl lg:text-7xl">
                            Jualan lebih cepat dengan landing page builder siap pakai.
                        </h1>
                        <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300 sm:text-lg">
                            Launchkit by novadigital.id membantu Anda bikin landing page profesional untuk produk digital, jasa, dan layanan pembuatan landing page tanpa perlu coding atau biaya bulanan.
                        </p>
                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            <a href="{{ route('register') }}" class="btn-primary">Beli Sekarang</a>
                            <a href="#preview" class="btn-secondary">Lihat Demo</a>
                        </div>
                        <div class="mt-6 flex flex-wrap gap-3 text-sm text-slate-400">
                            <span class="preview-chip">Permanent Access</span>
                            <span class="preview-chip">No Monthly Fee</span>
                            <span class="preview-chip">Drag & Drop Builder</span>
                        </div>
                    </div>
                </div>

                <div id="preview" class="panel overflow-hidden p-0">
                    <div class="border-b border-white/10 px-5 py-4">
                        <p class="text-xs font-black uppercase tracking-[0.24em] text-slate-500">Builder Preview</p>
                        <h2 class="mt-2 text-2xl font-black text-white">Preview interface yang siap dipakai</h2>
                    </div>
                    <div class="grid gap-0 lg:grid-cols-[1.2fr_0.8fr]">
                        <div class="border-b border-white/10 bg-slate-950/70 p-4 lg:border-b-0 lg:border-r">
                            <div class="rounded-[28px] border border-white/10 bg-[#08111f] p-4 shadow-[0_20px_80px_rgba(2,6,23,0.45)]">
                                <div class="mb-4 flex items-center gap-2">
                                    <span class="h-3 w-3 rounded-full bg-white/20"></span>
                                    <span class="h-3 w-3 rounded-full bg-white/20"></span>
                                    <span class="h-3 w-3 rounded-full bg-white/20"></span>
                                </div>
                                <div class="rounded-[24px] border border-emerald-400/10 bg-[linear-gradient(180deg,#07131f,#0b1626)] p-5">
                                    <span class="badge-soft !text-[10px]">Launchkit Builder</span>
                                    <h3 class="mt-4 max-w-sm text-3xl font-black leading-[1.02] text-white sm:text-4xl">Landing page untuk jualan digital product dan jasa.</h3>
                                    <p class="mt-3 max-w-md text-sm leading-7 text-slate-400">Drag block, edit copy, upload image, lalu export HTML yang siap dipasang di Scalev, Lynk.id, Wordpress, atau hosting manual.</p>
                                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                                        <div class="rounded-3xl border border-white/10 bg-white/5 p-4 text-sm text-slate-300">Responsive layout</div>
                                        <div class="rounded-3xl border border-white/10 bg-white/5 p-4 text-sm text-slate-300">Ready templates</div>
                                        <div class="rounded-3xl border border-white/10 bg-white/5 p-4 text-sm text-slate-300">Fast setup</div>
                                        <div class="rounded-3xl border border-white/10 bg-white/5 p-4 text-sm text-slate-300">Payment-ready blocks</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-950/50 p-4">
                            <div class="space-y-3">
                                @foreach ([
                                    ['Harga', 'Rp 99.000'],
                                    ['Akses', 'Sekali bayar, selamanya'],
                                    ['Cocok untuk', 'Produk digital & jasa'],
                                ] as [$label, $value])
                                    <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                                        <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-500">{{ $label }}</p>
                                        <p class="mt-2 text-lg font-black text-white">{{ $value }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                @foreach ([
                    'Tidak bisa bikin landing page sendiri',
                    'Ribet coding dan setup halaman jualan',
                    'Belum punya sistem jualan yang rapi',
                    'Tools builder lain terlalu mahal',
                ] as $problem)
                    <article class="panel p-5">
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-rose-300">Problem</p>
                        <h3 class="mt-4 text-lg font-black text-white">{{ $problem }}</h3>
                    </article>
                @endforeach
            </section>

            <section class="mt-8 panel p-7 sm:p-9">
                <div class="grid gap-8 lg:grid-cols-[0.95fr_1.05fr]">
                    <div>
                        <span class="badge-soft">Solusi</span>
                        <h2 class="mt-5 text-3xl font-black leading-tight text-white sm:text-5xl">Launchkit membuat proses bikin landing page terasa simpel dan cepat.</h2>
                        <p class="mt-4 text-base leading-8 text-slate-300">Anda tinggal isi konten, atur visual, lalu pakai builder drag & drop untuk menyesuaikan hasil. Cocok buat jual digital product, jasa desain, jasa landing page, sampai kebutuhan freelancer atau creator.</p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach ([
                            ['No coding needed', 'Semua diatur dari visual editor.'],
                            ['Ready-to-use system', 'Sudah ada struktur landing page yang conversion-focused.'],
                            ['Responsive output', 'Hasil final lebih rapi di desktop, tablet, dan mobile.'],
                            ['Fast launch', 'Dari ide ke halaman jualan dalam waktu singkat.'],
                        ] as [$title, $copy])
                            <article class="metric-card">
                                <h3 class="text-lg font-black text-white">{{ $title }}</h3>
                                <p class="mt-3 text-sm leading-7 text-slate-400">{{ $copy }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="mt-8 grid gap-6 lg:grid-cols-2">
                <div class="panel p-7 sm:p-8">
                    <p class="text-xs font-black uppercase tracking-[0.24em] text-slate-500">Features</p>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        @foreach ([
                            'Drag & drop builder',
                            'Responsive design',
                            'Ready templates',
                            'Payment-ready blocks',
                            'Fast setup',
                        ] as $feature)
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-4 text-sm font-semibold text-slate-200">{{ $feature }}</div>
                        @endforeach
                    </div>
                </div>

                <div class="panel p-7 sm:p-8">
                    <p class="text-xs font-black uppercase tracking-[0.24em] text-slate-500">Use Cases</p>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        @foreach ([
                            'Jual produk digital',
                            'Jasa desain',
                            'Jasa pembuatan landing page',
                            'Freelancer / creator',
                        ] as $useCase)
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-4 text-sm font-semibold text-slate-200">{{ $useCase }}</div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="mt-8 panel p-7 sm:p-9">
                <p class="text-xs font-black uppercase tracking-[0.24em] text-slate-500">Benefits</p>
                <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    @foreach ([
                        ['Hemat biaya', 'Tidak ada subscription bulanan.'],
                        ['Cepat launch', 'Lebih cepat publish halaman jualan.'],
                        ['Langsung jualan', 'CTA dan struktur halaman sudah siap pakai.'],
                        ['Simple & powerful', 'Builder tetap ringan tapi fleksibel.'],
                    ] as [$title, $copy])
                        <article class="metric-card">
                            <h3 class="text-lg font-black text-white">{{ $title }}</h3>
                            <p class="mt-3 text-sm leading-7 text-slate-400">{{ $copy }}</p>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="mt-8">
                <div class="panel p-8 text-center sm:p-10">
                    <p class="text-xs font-black uppercase tracking-[0.24em] text-slate-500">Pricing</p>
                    <h2 class="mt-4 text-4xl font-black text-white sm:text-6xl">Rp 99.000</h2>
                    <p class="mt-3 text-lg text-emerald-300">One-time payment · Permanent access · No monthly fee</p>
                    <div class="mt-8 flex justify-center">
                        <a href="{{ route('register') }}" class="btn-primary">Get Access Now</a>
                    </div>
                </div>
            </section>

            <section class="mt-8 panel p-7 sm:p-9">
                <p class="text-xs font-black uppercase tracking-[0.24em] text-slate-500">FAQ</p>
                <div class="mt-6 grid gap-4">
                    @foreach ([
                        ['Apakah perlu coding?', 'Tidak. Builder dirancang supaya Anda bisa edit visual dan konten langsung dari UI.'],
                        ['Bisa untuk jual jasa?', 'Bisa. Sangat cocok untuk jasa desain, jasa landing page, dan layanan digital lainnya.'],
                        ['Bisa dipakai selamanya?', 'Ya. Produk ini one-time payment dan aksesnya permanen.'],
                    ] as [$q, $a])
                        <article class="rounded-[28px] border border-white/10 bg-white/5 p-5">
                            <h3 class="text-lg font-black text-white">{{ $q }}</h3>
                            <p class="mt-3 text-sm leading-7 text-slate-400">{{ $a }}</p>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="mt-8 pb-10">
                <div class="hero-panel p-7 text-center sm:p-10">
                    <span class="badge-soft">Limited offer</span>
                    <h2 class="mt-5 text-3xl font-black text-white sm:text-5xl">Siap jualan lebih cepat dengan Launchkit by novadigital.id?</h2>
                    <p class="mx-auto mt-4 max-w-2xl text-base leading-8 text-slate-300">Daftar sekarang, lanjut order via WhatsApp, lalu mulai bangun landing page Anda tanpa biaya bulanan.</p>
                    <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                        <a href="{{ route('register') }}" class="btn-primary">Beli Sekarang</a>
                        <a href="{{ route('login') }}" class="btn-secondary">Saya Sudah Punya Akun</a>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>
</html>
