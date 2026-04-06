@extends('layouts.app', ['title' => 'Editor - '.$project->name])

@section('content')
    @include('projects.partials.landing-styles')

    <section
        x-data="adaptiveEditor({
            initial: @js($pageData),
            projectName: @js($project->name),
            presets: @js($allVisualPresets),
            previewConfig: @js($previewConfig),
            sectionRegistry: @js($sectionRegistry),
            uploadEndpoint: @js($uploadEndpoint),
        })"
        x-init="init()"
        class="grid gap-6 xl:grid-cols-[430px_minmax(0,1fr)]"
    >
        <form method="POST" action="{{ route('projects.update', $project) }}" class="panel h-[88vh] overflow-y-auto p-6">
            @csrf
            @method('PUT')

            <div class="border-b border-white/10 pb-5">
                <span class="badge-soft">Adaptive Visual Editor</span>
                <h1 class="mt-4 text-2xl font-black text-white">Edit copy, visual preset, gambar, dan section</h1>
                <p class="mt-3 text-sm leading-7 text-slate-400">Panel kiri untuk kontrol, panel kanan untuk preview browser realistis yang langsung ikut berubah.</p>
            </div>

            <div class="mt-5">
                <label class="field-label" for="name">Nama project</label>
                <input id="name" name="name" class="field-input" x-model="projectName">
            </div>

            <div class="mt-6 border-t border-white/10 pt-5">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-sm font-bold uppercase tracking-[0.24em] text-slate-400">Visual Preset</h2>
                    <span class="text-xs text-slate-500">10 preset universal</span>
                </div>
                <div class="preset-grid mt-4">
                    @foreach ($allVisualPresets as $preset)
                        <x-preset-card
                            :preset="$preset"
                            :recommended="in_array($project->niche, $preset['recommended_niches'] ?? [], true)"
                            x-on:click.prevent="applyPreset('{{ $preset['slug'] }}')"
                            x-bind:class="selectedPresetKey === '{{ $preset['slug'] }}' ? 'border-emerald-400/40 bg-emerald-400/10 ring-2 ring-emerald-400/30' : 'border-white/10 bg-slate-950/40 hover:bg-white/5'"
                        />
                    @endforeach
                </div>
            </div>

            <div class="mt-6 border-t border-white/10 pt-5">
                <h2 class="text-sm font-bold uppercase tracking-[0.24em] text-slate-400">Hero</h2>
                <div class="mt-4 space-y-4">
                    <div>
                        <label class="field-label">Badge</label>
                        <input class="field-input" x-model="hero.badge">
                    </div>
                    <div>
                        <label class="field-label">Headline</label>
                        <textarea class="field-input min-h-24" x-model="hero.headline"></textarea>
                    </div>
                    <div>
                        <label class="field-label">Subheadline</label>
                        <textarea class="field-input min-h-28" x-model="hero.subheadline"></textarea>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="field-label">CTA Text</label>
                            <input class="field-input" x-model="hero.cta_text">
                        </div>
                        <div>
                            <label class="field-label">CTA Link</label>
                            <input class="field-input" x-model="hero.cta_link">
                        </div>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="field-label">Hero Image</label>
                            <input class="field-input" x-model="hero.visual_image" placeholder="https://...">
                            <div class="mt-3 flex items-center gap-3">
                                <label class="btn-secondary cursor-pointer">
                                    <span>Upload + Crop</span>
                                    <input type="file" class="hidden" accept="image/*" @change="openCropperFromInput($event, { target: 'hero', field: 'visual_image', uploadingKey: 'hero_image' })">
                                </label>
                                <button type="button" class="btn-secondary !px-3 !py-2 !text-xs" @click="hero.visual_image = ''">Hapus</button>
                            </div>
                        </div>
                        <div>
                            <label class="field-label">Logo</label>
                            <input class="field-input" x-model="hero.logo" placeholder="https://...">
                            <div class="mt-3 flex items-center gap-3">
                                <label class="btn-secondary cursor-pointer">
                                    <span>Upload + Crop</span>
                                    <input type="file" class="hidden" accept="image/*" @change="openCropperFromInput($event, { target: 'hero', field: 'logo', uploadingKey: 'logo' })">
                                </label>
                                <button type="button" class="btn-secondary !px-3 !py-2 !text-xs" @click="hero.logo = ''">Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 border-t border-white/10 pt-5">
                <h2 class="text-sm font-bold uppercase tracking-[0.24em] text-slate-400">Theme</h2>
                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="field-label">Primary</label>
                        <input type="color" class="field-input h-14 p-2" x-model="theme.palette.primary">
                    </div>
                    <div>
                        <label class="field-label">Background</label>
                        <input type="color" class="field-input h-14 p-2" x-model="theme.palette.background">
                    </div>
                    <div>
                        <label class="field-label">Surface</label>
                        <input type="color" class="field-input h-14 p-2" x-model="theme.palette.surface">
                    </div>
                    <div>
                        <label class="field-label">Text</label>
                        <input type="color" class="field-input h-14 p-2" x-model="theme.palette.text">
                    </div>
                    <div>
                        <label class="field-label">Font preset</label>
                        <select class="field-input" x-model="theme.font">
                            @foreach ($themePresets as $font)
                                <option value="{{ $font }}">{{ $font }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="field-label">Spacing</label>
                        <select class="field-input" x-model="theme.spacing_density">
                            <option value="compact">Compact</option>
                            <option value="normal">Normal</option>
                            <option value="structured">Structured</option>
                            <option value="comfortable">Comfortable</option>
                            <option value="airy">Airy</option>
                        </select>
                    </div>
                    <div>
                        <label class="field-label">Hero style</label>
                        <select class="field-input" x-model="theme.hero_style">
                            <option value="saas-split">SaaS Split</option>
                            <option value="soft-showcase">Soft Showcase</option>
                            <option value="trust-stack">Trust Stack</option>
                            <option value="destination-banner">Destination Banner</option>
                            <option value="bold-product">Bold Product</option>
                            <option value="stock-banner">Stock Banner</option>
                            <option value="editorial">Editorial</option>
                            <option value="simple-convert">Simple Convert</option>
                            <option value="prestige-estate">Prestige Estate</option>
                            <option value="authority-profile">Authority Profile</option>
                            <option value="studio-case">Studio Case</option>
                        </select>
                    </div>
                    <div>
                        <label class="field-label">Button style</label>
                        <select class="field-input" x-model="theme.button_style">
                            <option value="rounded-pill">Rounded Pill</option>
                            <option value="rounded-lg">Rounded</option>
                            <option value="sharp">Sharp</option>
                            <option value="editorial">Editorial</option>
                            <option value="luxury">Luxury</option>
                        </select>
                    </div>
                    <div>
                        <label class="field-label">Pattern</label>
                        <select class="field-input" x-model="theme.background_pattern">
                            <option value="grid-glow">Grid Glow</option>
                            <option value="soft-grid">Soft Grid</option>
                            <option value="grid-subtle">Grid Subtle</option>
                            <option value="grid-industrial">Grid Industrial</option>
                            <option value="soft-blur">Soft Blur</option>
                            <option value="sunset-fade">Sunset Fade</option>
                            <option value="editorial-noise">Editorial Noise</option>
                            <option value="prestige-lines">Prestige Lines</option>
                            <option value="linen">Linen</option>
                            <option value="organic-wash">Organic Wash</option>
                        </select>
                    </div>
                    <div>
                        <label class="field-label">Heading scale</label>
                        <select class="field-input" x-model="theme.heading_scale">
                            <option value="lg">Large</option>
                            <option value="xl">Extra Large</option>
                            <option value="xxl">Hero XXL</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-6 border-t border-white/10 pt-5">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-sm font-bold uppercase tracking-[0.24em] text-slate-400">Sections</h2>
                    <button type="button" class="btn-primary !px-4 !py-2 !text-xs" @click="sectionModalOpen = true">Tambah Section</button>
                </div>

                <div class="mt-4 space-y-3">
                    <template x-for="(section, index) in sections" :key="section.id">
                        <div class="editor-card" :class="selectedIndex === index ? 'ring-2 ring-emerald-400/40' : ''">
                            <div class="flex items-start justify-between gap-3">
                                <div @click="selectedIndex = index" class="cursor-pointer">
                                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500" x-text="section.type"></p>
                                    <p class="mt-1 text-sm font-bold text-white" x-text="section.content.title || section.content.eyebrow || section.type"></p>
                                </div>
                                <div class="flex gap-2">
                                    <button type="button" class="rounded-full border border-white/10 px-3 py-1 text-xs font-semibold text-slate-300" @click="toggleSection(index)" x-text="section.enabled === false ? 'Show' : 'Hide'"></button>
                                    <button type="button" class="rounded-full border border-rose-400/20 px-3 py-1 text-xs font-semibold text-rose-200" @click="removeSection(index)">Hapus</button>
                                </div>
                            </div>
                            <div class="mt-3 flex gap-2">
                                <button type="button" class="btn-secondary !px-3 !py-2 !text-xs" @click="moveSection(index, -1)">Naik</button>
                                <button type="button" class="btn-secondary !px-3 !py-2 !text-xs" @click="moveSection(index, 1)">Turun</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="mt-6 border-t border-white/10 pt-5" x-show="currentSection()" x-cloak>
                <h2 class="text-sm font-bold uppercase tracking-[0.24em] text-slate-400">Quick Edit Section</h2>
                <div class="mt-4 space-y-4">
                    <template x-for="field in editableFields()" :key="field">
                        <div>
                            <label class="field-label" x-text="labelize(field)"></label>
                            <template x-if="fieldIsLong(field)">
                                <textarea class="field-input min-h-24" :value="currentValue(field)" @input="setCurrentField(field, $event.target.value)"></textarea>
                            </template>
                            <template x-if="!fieldIsLong(field)">
                                <input class="field-input" :value="currentValue(field)" @input="setCurrentField(field, $event.target.value)">
                            </template>
                        </div>
                    </template>

                    <template x-if="currentSection()?.type === 'image'">
                        <div class="rounded-[22px] border border-white/10 p-4">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label class="field-label">Gallery Layout</label>
                                    <select class="field-input" x-model="currentSection().content.layout">
                                        <option value="grid-2">Grid 2</option>
                                        <option value="grid-3">Grid 3</option>
                                        <option value="masonry-simple">Masonry Simple</option>
                                        <option value="slider-style-preview">Slider Style Preview</option>
                                        <option value="collage-clean">Collage Clean</option>
                                    </select>
                                </div>
                                <div class="flex items-end">
                                    <label class="btn-primary w-full cursor-pointer text-center">
                                        <span>Tambah Gambar + Crop</span>
                                        <input type="file" class="hidden" accept="image/*" @change="addImageSectionItem($event)">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div x-show="Array.isArray(currentSection()?.content?.items)">
                        <div class="mb-3 flex items-center justify-between">
                            <label class="field-label !mb-0">Items</label>
                            <button type="button" class="btn-secondary !px-3 !py-2 !text-xs" @click="addItem()">Tambah Item</button>
                        </div>
                        <div class="space-y-3">
                            <template x-for="(item, itemIndex) in currentSection().content.items" :key="item.id || itemIndex">
                                <div class="rounded-2xl border border-white/10 p-3">
                                    <template x-if="typeof item === 'string'">
                                        <div class="space-y-2">
                                            <textarea class="field-input min-h-20" :value="item" @input="setStringItem(itemIndex, $event.target.value)"></textarea>
                                            <button type="button" class="btn-secondary !px-3 !py-2 !text-xs" @click="removeItem(itemIndex)">Hapus</button>
                                        </div>
                                    </template>
                                    <template x-if="typeof item === 'object'">
                                        <div class="space-y-2">
                                            <template x-for="(fieldValue, fieldKey) in item" :key="fieldKey">
                                                <div>
                                                    <label class="field-label" x-text="labelize(fieldKey)"></label>
                                                    <template x-if="fieldKey === 'image' || fieldKey === 'image_url'">
                                                        <div class="space-y-3">
                                                            <input class="field-input" :value="fieldValue" @input="setObjectItem(itemIndex, fieldKey, $event.target.value)">
                                                            <div class="flex items-center gap-3">
                                                                <label class="btn-secondary cursor-pointer">
                                                                    <span>Upload + Crop</span>
                                                                    <input type="file" class="hidden" accept="image/*" @change="openCropperFromInput($event, { target: 'item', itemIndex, field: fieldKey, uploadingKey: 'item-' + itemIndex })">
                                                                </label>
                                                                <button type="button" class="btn-secondary !px-3 !py-2 !text-xs" @click="setObjectItem(itemIndex, fieldKey, '')">Hapus</button>
                                                            </div>
                                                            <img x-show="fieldValue" :src="fieldValue" alt="" class="h-28 w-full rounded-2xl object-cover">
                                                        </div>
                                                    </template>
                                                    <template x-if="fieldKey !== 'image' && fieldKey !== 'image_url'">
                                                        <textarea class="field-input min-h-20" :value="fieldValue" @input="setObjectItem(itemIndex, fieldKey, $event.target.value)"></textarea>
                                                    </template>
                                                </div>
                                            </template>
                                            <div class="flex gap-3">
                                                <button type="button" class="btn-secondary !px-3 !py-2 !text-xs" @click="moveItem(itemIndex, -1)">Naik</button>
                                                <button type="button" class="btn-secondary !px-3 !py-2 !text-xs" @click="moveItem(itemIndex, 1)">Turun</button>
                                                <button type="button" class="btn-secondary !px-3 !py-2 !text-xs" @click="removeItem(itemIndex)">Hapus</button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="edited_data" :value="serialized">

            <div class="mt-6 flex flex-wrap gap-3 border-t border-white/10 pt-5">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
                <a href="{{ route('projects.export', $project) }}" class="btn-secondary">Export HTML</a>
            </div>
        </form>

        <div class="panel flex h-[88vh] flex-col overflow-hidden p-0">
            <div class="border-b border-white/10 px-6 py-4">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm font-bold uppercase tracking-[0.24em] text-slate-400">Live Preview</p>
                    <div class="flex items-center gap-2">
                        <button type="button" class="btn-secondary !px-3 !py-2 !text-xs" :class="previewMode === 'desktop' ? '!bg-white/15' : ''" @click="setPreviewMode('desktop')">Web</button>
                        <button type="button" class="btn-secondary !px-3 !py-2 !text-xs" :class="previewMode === 'mobile' ? '!bg-white/15' : ''" @click="setPreviewMode('mobile')">HP</button>
                    </div>
                </div>
            </div>

            <div x-ref="previewHost" class="flex-1 overflow-hidden p-4 md:p-6">
                <x-preview-shell :preview-config="$previewConfig">
                    <div x-ref="previewViewport" class="relative overflow-y-auto overflow-x-hidden" :style="previewViewportStyle()">
                        <div x-ref="previewCanvasWrap" class="relative" :style="previewCanvasWrapStyle()">
                            <div x-ref="previewCanvas" class="absolute left-0 top-0 origin-top-left" :style="previewCanvasStyle()">
                            @include('projects.partials.landing-render', ['pageData' => $pageData, 'mode' => 'editor'])
                            </div>
                        </div>
                    </div>
                </x-preview-shell>
            </div>
        </div>

        <div x-show="sectionModalOpen" x-cloak class="cropper-backdrop" @click="sectionModalOpen = false"></div>
        <div x-show="sectionModalOpen" x-cloak class="cropper-modal" @click.stop>
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.24em] text-slate-400">Tambah Section</p>
                    <h2 class="mt-2 text-2xl font-black text-white">Pilih section baru</h2>
                </div>
                <button type="button" class="btn-secondary !px-3 !py-2 !text-xs" @click="sectionModalOpen = false">Tutup</button>
            </div>
            <div class="preset-grid mt-5">
                <template x-for="sectionType in sectionRegistry" :key="sectionType.type">
                    <button type="button" class="editor-card text-left transition hover:bg-white/5" @click="createSection(sectionType.type)">
                        <p class="text-sm font-black text-white" x-text="sectionType.label"></p>
                        <p class="mt-2 text-sm leading-7 text-slate-400" x-text="sectionType.description"></p>
                    </button>
                </template>
            </div>
        </div>

        <div x-show="cropModal.open" x-cloak class="cropper-backdrop"></div>
        <div x-show="cropModal.open" x-cloak class="cropper-modal">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.24em] text-slate-400">Crop Image</p>
                    <h2 class="mt-2 text-2xl font-black text-white">Atur area crop gambar</h2>
                </div>
                <button type="button" class="btn-secondary !px-3 !py-2 !text-xs" @click="closeCropper()">Tutup</button>
            </div>

            <div class="mt-4 flex flex-wrap gap-2">
                <button type="button" class="btn-secondary !px-3 !py-2 !text-xs" @click="setCropRatio(1)">1:1</button>
                <button type="button" class="btn-secondary !px-3 !py-2 !text-xs" @click="setCropRatio(4 / 5)">4:5</button>
                <button type="button" class="btn-secondary !px-3 !py-2 !text-xs" @click="setCropRatio(16 / 9)">16:9</button>
                <button type="button" class="btn-secondary !px-3 !py-2 !text-xs" @click="setCropRatio(3 / 4)">3:4</button>
                <button type="button" class="btn-secondary !px-3 !py-2 !text-xs" @click="setCropRatio(NaN)">Free</button>
            </div>

            <div class="cropper-stage">
                <img x-ref="cropperImage" alt="Crop image" class="max-h-[70vh] w-full object-contain">
            </div>

            <div class="mt-5 flex flex-wrap gap-3">
                <button type="button" class="btn-primary" @click="confirmCrop()">Simpan Crop</button>
                <button type="button" class="btn-secondary" @click="closeCropper()">Batal</button>
            </div>
        </div>
    </section>

    <script>
        function adaptiveEditor({ initial, projectName, presets, previewConfig, sectionRegistry, uploadEndpoint }) {
            return {
                projectName,
                meta: initial.meta ?? {},
                theme: initial.theme ?? {},
                hero: initial.hero ?? {},
                sections: initial.sections ?? [],
                presets: presets ?? [],
                previewConfig: previewConfig ?? {},
                sectionRegistry: sectionRegistry ?? [],
                uploadEndpoint,
                previewMode: 'desktop',
                previewScale: 1,
                previewCanvasHeight: 0,
                previewFrameHeight: 0,
                sectionModalOpen: false,
                cropModal: { open: false, fileName: null, target: null },
                cropper: null,
                selectedIndex: 0,
                selectedPresetKey: initial.theme?.visual_preset_key ?? 'clean-commerce',
                get serialized() {
                    return JSON.stringify({
                        meta: { ...this.meta, project_name: this.projectName },
                        theme: { ...this.theme, visual_preset_key: this.selectedPresetKey || null },
                        hero: this.hero,
                        sections: this.sections,
                    });
                },
                init() {
                    this.theme = this.normalizeTheme(this.theme ?? {});
                    this.$nextTick(() => {
                        this.syncPreviewScale();
                        this.resizeObserver = new ResizeObserver(() => this.syncPreviewScale());
                        this.resizeObserver.observe(this.$refs.previewHost);
                        this.resizeObserver.observe(this.$refs.previewCanvas);
                        window.addEventListener('resize', this.syncPreviewScale.bind(this));
                    });
                },
                normalizeTheme(theme) {
                    return {
                        ...theme,
                        palette: {
                            primary: theme.palette?.primary ?? '#10b981',
                            secondary: theme.palette?.secondary ?? '#d1fae5',
                            accent: theme.palette?.accent ?? '#34d399',
                            text: theme.palette?.text ?? '#ecfdf5',
                            background: theme.palette?.background ?? '#07110f',
                            surface: theme.palette?.surface ?? '#0c1714',
                            muted: theme.palette?.muted ?? 'rgba(209, 250, 229, 0.72)',
                        },
                        font: theme.font ?? 'Plus Jakarta Sans',
                        button_style: theme.button_style ?? 'rounded-lg',
                        hero_style: theme.hero_style ?? 'simple-convert',
                        background_pattern: theme.background_pattern ?? 'soft-grid',
                        spacing_density: theme.spacing_density ?? 'normal',
                        heading_scale: theme.heading_scale ?? 'lg',
                    };
                },
                setPreviewMode(mode) {
                    this.previewMode = mode;
                    this.$nextTick(() => this.syncPreviewScale());
                },
                syncPreviewScale() {
                    const config = this.previewConfig[this.previewMode];
                    if (!config || !this.$refs.previewHost) return;
                    const hostWidth = this.$refs.previewHost.clientWidth - (this.previewMode === 'mobile' ? 72 : 24);
                    this.previewScale = Math.min(1, Math.max(hostWidth, 260) / config.canvas_width);
                    this.previewFrameHeight = Math.max(520, this.$refs.previewHost.clientHeight - 12);
                    this.$nextTick(() => {
                        const measured = this.$refs.previewCanvas?.scrollHeight ?? config.canvas_height;
                        this.previewCanvasHeight = Math.max(measured, config.canvas_height);
                    });
                },
                previewViewportStyle() {
                    const config = this.previewConfig[this.previewMode];
                    const width = config.canvas_width * this.previewScale;
                    const height = this.previewFrameHeight || config.frame_height;
                    return `width:${width}px;height:${height}px;`;
                },
                previewCanvasWrapStyle() {
                    const config = this.previewConfig[this.previewMode];
                    const contentHeight = (this.previewCanvasHeight || config.canvas_height) * this.previewScale;
                    return `width:${config.canvas_width * this.previewScale}px;height:${contentHeight}px;min-height:${contentHeight}px;`;
                },
                previewCanvasStyle() {
                    const config = this.previewConfig[this.previewMode];
                    const minHeight = this.previewCanvasHeight || config.canvas_height;
                    return `width:${config.canvas_width}px;min-height:${minHeight}px;transform:scale(${this.previewScale});`;
                },
                currentSection() {
                    return this.sections[this.selectedIndex] ?? null;
                },
                sectionOrder(id) {
                    return this.sections.findIndex((item) => item.id === id);
                },
                applyPreset(slug) {
                    const preset = this.presets.find((item) => item.slug === slug);
                    if (!preset) return;
                    this.selectedPresetKey = slug;
                    this.theme = this.normalizeTheme({
                        ...this.theme,
                        palette: { ...this.theme.palette, ...preset.palette },
                        font: preset.font_preset,
                        button_style: preset.button_style,
                        hero_style: preset.hero_style,
                        background_pattern: preset.background_pattern,
                        spacing_density: preset.section_spacing,
                    });
                },
                previewVars() {
                    return `--lp-primary:${this.theme.palette.primary};--lp-secondary:${this.theme.palette.secondary};--lp-accent:${this.theme.palette.accent};--lp-text:${this.theme.palette.text};--lp-background:${this.theme.palette.background};--lp-surface:${this.theme.palette.surface};--lp-muted:${this.theme.palette.muted};--lp-font:${this.theme.font};`;
                },
                moveSection(index, direction) {
                    const target = index + direction;
                    if (target < 0 || target >= this.sections.length) return;
                    const copy = [...this.sections];
                    const [item] = copy.splice(index, 1);
                    copy.splice(target, 0, item);
                    this.sections = copy;
                    this.selectedIndex = target;
                },
                removeSection(index) {
                    this.sections.splice(index, 1);
                    this.selectedIndex = Math.max(0, this.selectedIndex - 1);
                },
                toggleSection(index) {
                    this.sections[index].enabled = this.sections[index].enabled === false;
                },
                editableFields() {
                    const section = this.currentSection();
                    if (!section?.content) return [];
                    return Object.keys(section.content).filter((field) => field !== 'items');
                },
                currentValue(field) {
                    return this.currentSection()?.content?.[field] ?? '';
                },
                setCurrentField(field, value) {
                    if (this.currentSection()?.content?.[field] !== undefined) {
                        this.currentSection().content[field] = value;
                    }
                },
                fieldIsLong(field) {
                    return ['description', 'copy', 'subtitle', 'subheadline', 'answer'].includes(field);
                },
                addItem() {
                    const section = this.currentSection();
                    const items = section?.content?.items;
                    if (!Array.isArray(items)) return;

                    if (section.type === 'image') {
                        items.push({ id: `img_${Date.now()}`, image_url: '', caption: 'Caption baru' });
                        return;
                    }

                    if (items.length === 0 || typeof items[0] === 'string') {
                        items.push('Item baru');
                        return;
                    }

                    const sample = items[0];
                    const fresh = {};
                    Object.keys(sample).forEach((key) => {
                        fresh[key] = key === 'price' ? 'Rp 0' : key === 'id' ? `item_${Date.now()}` : '';
                    });
                    items.push(fresh);
                },
                addImageSectionItem(event) {
                    const section = this.currentSection();
                    if (!section || section.type !== 'image') return;
                    const index = section.content.items.length;
                    section.content.items.push({ id: `img_${Date.now()}`, image_url: '', caption: 'Caption baru' });
                    this.openCropperFromInput(event, { target: 'item', itemIndex: index, field: 'image_url', uploadingKey: `item-${index}` });
                },
                removeItem(index) {
                    const items = this.currentSection()?.content?.items;
                    if (!Array.isArray(items)) return;
                    items.splice(index, 1);
                },
                moveItem(index, direction) {
                    const items = this.currentSection()?.content?.items;
                    if (!Array.isArray(items)) return;
                    const target = index + direction;
                    if (target < 0 || target >= items.length) return;
                    const copy = [...items];
                    const [item] = copy.splice(index, 1);
                    copy.splice(target, 0, item);
                    this.currentSection().content.items = copy;
                },
                setStringItem(index, value) {
                    this.currentSection().content.items[index] = value;
                },
                setObjectItem(index, key, value) {
                    this.currentSection().content.items[index][key] = value;
                },
                labelize(value) {
                    return String(value).replaceAll('_', ' ').replace(/\b\w/g, (char) => char.toUpperCase());
                },
                registryItem(type) {
                    return this.sectionRegistry.find((item) => item.type === type) ?? null;
                },
                createSection(type) {
                    const registry = this.registryItem(type);
                    if (!registry) return;
                    const section = {
                        id: `${type}-${Date.now()}`,
                        type,
                        enabled: true,
                        content: JSON.parse(JSON.stringify(registry.default_content ?? {})),
                        style: JSON.parse(JSON.stringify(registry.default_style ?? { card_radius: 'xl', gap: 'md' })),
                    };
                    this.sections.push(section);
                    this.selectedIndex = this.sections.length - 1;
                    this.sectionModalOpen = false;
                },
                async sendAsset(file, filename = 'upload.png') {
                    const token = document.querySelector('meta[name="csrf-token"]')?.content;
                    const formData = new FormData();
                    formData.append('asset', file, filename);
                    const response = await fetch(this.uploadEndpoint, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });
                    if (!response.ok) throw new Error('Upload gagal');
                    return response.json();
                },
                openCropperFromInput(event, target) {
                    const file = event.target.files?.[0];
                    if (!file) return;
                    this.cropModal.fileName = file.name;
                    this.cropModal.target = target;
                    this.cropModal.open = true;

                    const reader = new FileReader();
                    reader.onload = () => {
                        this.$nextTick(() => {
                            this.$refs.cropperImage.src = reader.result;
                            if (this.cropper) this.cropper.destroy();
                            this.cropper = new window.Cropper(this.$refs.cropperImage, {
                                viewMode: 1,
                                autoCropArea: 1,
                                responsive: true,
                            });
                        });
                    };
                    reader.readAsDataURL(file);
                    event.target.value = '';
                },
                setCropRatio(ratio) {
                    if (this.cropper) this.cropper.setAspectRatio(ratio);
                },
                closeCropper() {
                    this.cropModal.open = false;
                    this.cropModal.fileName = null;
                    this.cropModal.target = null;
                    if (this.cropper) {
                        this.cropper.destroy();
                        this.cropper = null;
                    }
                },
                async confirmCrop() {
                    if (!this.cropper || !this.cropModal.target) return;
                    const canvas = this.cropper.getCroppedCanvas({ imageSmoothingQuality: 'high' });
                    const blob = await new Promise((resolve) => canvas.toBlob(resolve, 'image/png', 0.92));
                    const result = await this.sendAsset(blob, this.cropModal.fileName || 'crop.png');
                    const target = this.cropModal.target;

                    if (target.target === 'hero') {
                        this.hero[target.field] = result.url;
                    } else if (target.target === 'item') {
                        this.setObjectItem(target.itemIndex, target.field, result.url);
                    }

                    this.closeCropper();
                },
            };
        }
    </script>
@endsection
