@extends('layouts.app', ['title' => 'Buat Project - LaunchKit Adaptive'])

@section('content')
    <section
        class="grid gap-6 xl:grid-cols-[360px_minmax(0,1fr)]"
        x-data="projectWizard({
            catalog: @js($nicheCatalog),
            styles: @js($designStyles),
            initialNiche: @js(old('niche', 'digital-product')),
            initialStyle: @js(old('design_style', 'clean-commerce')),
        })"
    >
        <aside class="hero-panel h-fit p-6 xl:sticky xl:top-24">
            <div class="relative z-10">
                <span class="badge-soft">Generator Wizard</span>
                <h1 class="mt-5 text-3xl font-black leading-tight text-white">Susun brief seperti wizard modern, lalu generate landing page adaptif.</h1>
                <p class="mt-4 text-sm leading-7 text-slate-300">
                    Form ini sudah dibagi menjadi dasar bisnis, penawaran, setup marketing, audience, identitas visual, dan review akhir supaya hasil generator lebih rapi.
                </p>

                <div class="mt-6 space-y-3">
                    @foreach ([
                        1 => 'Informasi Dasar',
                        2 => 'Penawaran',
                        3 => 'Marketing Setup',
                        4 => 'Audience',
                        5 => 'Identitas Visual',
                        6 => 'Review & Generate',
                    ] as $index => $label)
                        <button
                            type="button"
                            class="flex w-full items-center gap-4 rounded-2xl border px-4 py-4 text-left transition"
                            :class="step === {{ $index }} ? 'border-emerald-400/40 bg-emerald-400/10 text-white' : 'border-white/10 bg-slate-950/40 text-slate-300'"
                            @click="step = {{ $index }}"
                        >
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-full text-sm font-black"
                                :class="step === {{ $index }} ? 'bg-emerald-400 text-slate-950' : 'bg-white/10 text-white'">{{ $index }}</span>
                            <span class="text-sm font-semibold">{{ $label }}</span>
                        </button>
                    @endforeach
                </div>

                <div class="mt-6 rounded-[28px] border border-white/10 p-5" :style="previewCardStyle()">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="preview-chip" x-text="currentTheme().label"></span>
                        <span class="preview-chip" x-text="selectedStyle"></span>
                    </div>
                    <h2 class="mt-5 text-2xl font-black" :style="{ color: currentTheme().palette?.text || '#fff' }">Preview mood niche & style</h2>
                    <p class="mt-4 text-sm leading-7" :style="{ color: currentTheme().palette?.muted || 'rgba(255,255,255,0.7)' }" x-text="currentTheme().copy_tone"></p>
                    <div class="mt-4 flex gap-2">
                        <template x-for="swatch in previewSwatches()" :key="swatch">
                            <span class="h-9 w-9 rounded-2xl border border-white/10" :style="{ background: swatch }"></span>
                        </template>
                    </div>
                </div>
            </div>
        </aside>

        <form method="POST" action="{{ route('projects.store') }}" enctype="multipart/form-data" class="panel p-8">
            @csrf

            <div x-show="step === 1" x-cloak class="space-y-6">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.24em] text-slate-400">Step 1</p>
                    <h2 class="mt-2 text-3xl font-black text-white">Informasi dasar</h2>
                </div>
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="md:col-span-2"><label class="field-label">Nama Project</label><input class="field-input" name="nama_project" value="{{ old('nama_project') }}" required></div>
                    <div><label class="field-label">Nama Brand / Bisnis</label><input class="field-input" name="nama_brand_bisnis" value="{{ old('nama_brand_bisnis') }}" required></div>
                    <div><label class="field-label">Nama Produk / Layanan</label><input class="field-input" name="nama_produk_layanan" value="{{ old('nama_produk_layanan') }}" required></div>
                    <div><label class="field-label">Tipe Bisnis</label><select class="field-input" name="business_type" required>@foreach($businessTypes as $type)<option value="{{ $type }}">{{ ucfirst($type) }}</option>@endforeach</select></div>
                    <div><label class="field-label">Niche Bisnis</label><select class="field-input" name="niche" x-model="selectedNiche" required>@foreach($nicheOptions as $option)<option value="{{ $option['value'] }}">{{ $option['label'] }}</option>@endforeach</select></div>
                    <div class="md:col-span-2"><label class="field-label">Deskripsi Singkat</label><textarea class="field-input min-h-28" name="deskripsi_singkat" required>{{ old('deskripsi_singkat') }}</textarea></div>
                </div>
            </div>

            <div x-show="step === 2" x-cloak class="space-y-6">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.24em] text-slate-400">Step 2</p>
                    <h2 class="mt-2 text-3xl font-black text-white">Penawaran</h2>
                </div>
                <div class="grid gap-6">
                    <div><label class="field-label">Masalah Utama</label><textarea class="field-input min-h-28" name="masalah_utama" required>{{ old('masalah_utama') }}</textarea></div>
                    <div><label class="field-label">Manfaat Utama</label><textarea class="field-input min-h-28" name="manfaat_utama" required>{{ old('manfaat_utama') }}</textarea></div>
                    <div><label class="field-label">Fitur Utama</label><textarea class="field-input min-h-28" name="fitur_utama" required>{{ old('fitur_utama') }}</textarea></div>
                    <div><label class="field-label">Keunggulan</label><textarea class="field-input min-h-24" name="keunggulan_kompetitor">{{ old('keunggulan_kompetitor') }}</textarea></div>
                    <div><label class="field-label">Testimoni</label><textarea class="field-input min-h-24" name="testimoni">{{ old('testimoni') }}</textarea></div>
                    <div><label class="field-label">FAQ Dasar</label><textarea class="field-input min-h-24" name="faq_dasar">{{ old('faq_dasar') }}</textarea></div>
                    <div class="grid gap-6 md:grid-cols-2">
                        <div><label class="field-label">Harga</label><input class="field-input" name="harga" value="{{ old('harga') }}" required></div>
                        <div><label class="field-label">CTA Utama</label><input class="field-input" name="cta_utama" value="{{ old('cta_utama', 'Hubungi Sekarang') }}" required></div>
                    </div>
                    <div><label class="field-label">Link CTA</label><input class="field-input" name="cta_link" value="{{ old('cta_link') }}" required></div>
                </div>
            </div>

            <div x-show="step === 3" x-cloak class="space-y-6">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.24em] text-slate-400">Step 3</p>
                    <h2 class="mt-2 text-3xl font-black text-white">Marketing setup</h2>
                </div>
                <div class="grid gap-6 md:grid-cols-2">
                    <div><label class="field-label">Platform Tujuan</label><select class="field-input" name="platform_target">@foreach($platformTargets as $item)<option value="{{ $item }}">{{ $item }}</option>@endforeach</select></div>
                    <div><label class="field-label">Sumber Traffic</label><select class="field-input" name="traffic_source">@foreach($trafficSources as $item)<option value="{{ $item }}">{{ $item }}</option>@endforeach</select></div>
                    <div><label class="field-label">Tujuan Utama</label><select class="field-input" name="goal">@foreach($goals as $item)<option value="{{ $item }}">{{ $item }}</option>@endforeach</select></div>
                    <div><label class="field-label">Awareness Level</label><select class="field-input" name="awareness_level">@foreach($awarenessLevels as $item)<option value="{{ $item }}">{{ $item }}</option>@endforeach</select></div>
                    <div><label class="field-label">Framework Copywriting</label><select class="field-input" name="copywriting_framework">@foreach($copyFrameworks as $item)<option value="{{ $item['slug'] }}">{{ $item['name'] }}</option>@endforeach</select></div>
                    <div><label class="field-label">Gaya Bahasa</label><select class="field-input" name="language_tone">@foreach($languageTones as $item)<option value="{{ $item }}">{{ $item }}</option>@endforeach</select></div>
                    <div><label class="field-label">Preferensi Visual</label><select class="field-input" name="visual_preference">@foreach($visualOptions as $option)<option value="{{ $option }}">{{ ucfirst($option) }}</option>@endforeach</select></div>
                    <div><label class="field-label">Tone Copy</label><select class="field-input" name="tone_copy">@foreach($toneOptions as $option)<option value="{{ $option }}">{{ ucfirst($option) }}</option>@endforeach</select></div>
                </div>
            </div>

            <div x-show="step === 4" x-cloak class="space-y-6">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.24em] text-slate-400">Step 4</p>
                    <h2 class="mt-2 text-3xl font-black text-white">Audience</h2>
                </div>
                <div class="grid gap-6">
                    <div>
                        <label class="field-label">Target Audience</label>
                        <select class="field-input min-h-48" name="target_audience[]" multiple>
                            @foreach($audienceOptions as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="field-label">Pain Point Audience</label><textarea class="field-input min-h-24" name="pain_point_audience">{{ old('pain_point_audience') }}</textarea></div>
                    <div><label class="field-label">Desire / Goal Audience</label><textarea class="field-input min-h-24" name="desire_goal_audience">{{ old('desire_goal_audience') }}</textarea></div>
                    <div><label class="field-label">Objection Audience</label><textarea class="field-input min-h-24" name="objection_audience">{{ old('objection_audience') }}</textarea></div>
                    <div><label class="field-label">Target Market Ringkas</label><input class="field-input" name="target_market" value="{{ old('target_market') }}" placeholder="Dipakai di hero dan subheadline" required></div>
                </div>
            </div>

            <div x-show="step === 5" x-cloak class="space-y-6">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.24em] text-slate-400">Step 5</p>
                    <h2 class="mt-2 text-3xl font-black text-white">Identitas visual</h2>
                </div>
                <div class="grid gap-6 md:grid-cols-2">
                    <div><label class="field-label">Warna Brand</label><select class="field-input" name="brand_color_family">@foreach($brandColors as $item)<option value="{{ $item }}">{{ $item }}</option>@endforeach</select></div>
                    <div><label class="field-label">Background Mode</label><select class="field-input" name="background_mode">@foreach($backgroundModes as $item)<option value="{{ $item }}">{{ $item }}</option>@endforeach</select></div>
                    <div><label class="field-label">Font Preference</label><select class="field-input" name="font_preference">@foreach($fontOptions as $item)<option value="{{ $item }}">{{ $item }}</option>@endforeach</select></div>
                    <div><label class="field-label">Preset Spacing</label><select class="field-input" name="spacing_preset">@foreach($spacingPresets as $item)<option value="{{ $item }}">{{ $item }}</option>@endforeach</select></div>
                    <input type="hidden" name="visual_preset" :value="selectedStyle">
                    <input type="hidden" name="design_style" :value="selectedStyle">
                    <div class="md:col-span-2">
                        <label class="field-label">Gaya Desain / Referensi</label>
                        <div class="preset-grid">
                            @foreach ($designStyles as $preset)
                                <x-preset-card
                                    :preset="$preset"
                                    x-on:click.prevent="selectedStyle = '{{ $preset['slug'] }}'"
                                    x-bind:class="selectedStyle === '{{ $preset['slug'] }}' ? 'border-emerald-400/40 bg-emerald-400/10 ring-2 ring-emerald-400/30' : 'border-white/10 bg-slate-950/40 hover:bg-white/5'"
                                />
                            @endforeach
                        </div>
                    </div>
                    <div><label class="field-label">Logo</label><input class="field-input file:mr-4 file:rounded-xl file:border-0 file:bg-emerald-400 file:px-4 file:py-2 file:font-bold file:text-slate-950" type="file" name="logo" accept="image/*"></div>
                    <div><label class="field-label">Hero Image</label><input class="field-input file:mr-4 file:rounded-xl file:border-0 file:bg-emerald-400 file:px-4 file:py-2 file:font-bold file:text-slate-950" type="file" name="hero_image" accept="image/*"></div>
                </div>
            </div>

            <div x-show="step === 6" x-cloak class="space-y-6">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.24em] text-slate-400">Step 6</p>
                    <h2 class="mt-2 text-3xl font-black text-white">Review & Generate</h2>
                </div>
                <div class="rounded-[28px] border border-white/10 bg-slate-950/40 p-6 text-sm leading-7 text-slate-300">
                    Review singkat:
                    <ul class="mt-3 space-y-2">
                        <li>Niche dan design style akan mempengaruhi hero, layout, section order, CTA feel, dan visual mood.</li>
                        <li>Framework copywriting akan dipakai untuk mengarahkan struktur pesan yang di-generate.</li>
                        <li>Audience, awareness, goal, dan traffic source akan ikut membentuk headline, subheadline, dan CTA emphasis.</li>
                    </ul>
                </div>
            </div>

            <div class="mt-8 flex flex-wrap items-center justify-between gap-3 border-t border-white/10 pt-6">
                <button type="button" class="btn-secondary" @click="step = Math.max(1, step - 1)">Langkah Sebelumnya</button>
                <div class="flex gap-3">
                    <button type="button" class="btn-secondary" x-show="step < maxStep" x-cloak @click="step = Math.min(maxStep, step + 1)">Lanjut</button>
                    <button type="submit" class="btn-primary">Generate Adaptive Landing Page</button>
                </div>
            </div>
        </form>
    </section>

    <script>
        function projectWizard({ catalog, styles, initialNiche, initialStyle }) {
            return {
                step: 1,
                maxStep: 6,
                catalog: catalog ?? {},
                styles: styles ?? [],
                selectedNiche: initialNiche,
                selectedStyle: initialStyle,
                currentTheme() {
                    return this.catalog[this.selectedNiche] ?? Object.values(this.catalog)[0] ?? {};
                },
                previewSwatches() {
                    const style = this.styles.find((item) => item.slug === this.selectedStyle);
                    const palette = style?.palette ?? this.currentTheme().palette ?? {};
                    return [palette.primary, palette.accent, palette.background].filter(Boolean);
                },
                previewCardStyle() {
                    const style = this.styles.find((item) => item.slug === this.selectedStyle);
                    const palette = style?.palette ?? this.currentTheme().palette ?? {};
                    return `background:${palette.background || '#0f172a'}; color:${palette.text || '#fff'};`;
                },
            };
        }
    </script>
@endsection
