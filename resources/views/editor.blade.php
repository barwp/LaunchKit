@extends('layouts.app', ['title' => 'Editor - '.$project->name])

@section('content')
    @include('projects.partials.landing-styles')

    <section
        x-data="adaptiveBuilder({
            initial: @js($pageData),
            projectName: @js($project->name),
            presets: @js($allVisualPresets),
            previewConfig: @js($previewConfig),
            sectionRegistry: @js($sectionRegistry),
            uploadEndpoint: @js($uploadEndpoint),
        })"
        x-init="init()"
        class="space-y-4"
    >
        <form method="POST" action="{{ route('projects.update', $project) }}" class="space-y-4" @submit.prevent="submitEditorForm($event)">
            @csrf
            @method('PUT')

            <x-topbar-editor :project="$project" />

            <div class="builder-layout">
                <aside class="builder-sidebar">
                    <div class="builder-project-card">
                        <span class="badge-soft">Builder Project</span>
                        <input name="name" class="field-input mt-4" x-model="projectName">
                        <p class="mt-3 text-sm leading-7 text-slate-400">Visual builder modern untuk edit section, styles, copy, media, dan preview multi-device.</p>
                    </div>

                    <div class="builder-panel mt-4">
                        <div class="builder-topbar-group">
                            <button type="button" class="builder-tab" :class="leftTab === 'layers' || leftTab === 'editor' ? 'is-active' : ''" @click="leftTab = 'layers'">Layers</button>
                            <button type="button" class="builder-tab" :class="leftTab === 'media' ? 'is-active' : ''" @click="leftTab = 'media'">Media</button>
                            <button type="button" class="builder-tab" :class="leftTab === 'blocks' ? 'is-active' : ''" @click="leftTab = 'blocks'">Blocks</button>
                            <button type="button" class="builder-tab" :class="leftTab === 'styles' || leftTab === 'visual' ? 'is-active' : ''" @click="leftTab = 'styles'">Styles</button>
                        </div>
                    </div>

                    <div x-show="leftTab === 'layers' || leftTab === 'editor'" x-cloak>
                        <x-layers-panel />
                    </div>

                    <div x-show="leftTab === 'media'" x-cloak class="builder-panel">
                        <div class="builder-panel-header">
                            <div>
                                <p class="builder-eyebrow">Media</p>
                                <h3 class="builder-panel-title">Uploaded Assets</h3>
                            </div>
                        </div>
                        <div class="builder-media-grid">
                            <template x-for="image in mediaLibrary()" :key="image.url">
                                <button type="button" class="builder-media-card" @click="applyMediaToSelected(image.url)">
                                    <img :src="image.url" alt="" class="builder-media-thumb">
                                    <span x-text="image.label"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div x-show="leftTab === 'blocks'" x-cloak>
                        <x-blocks-panel />
                    </div>

                    <div x-show="leftTab === 'styles' || leftTab === 'visual'" x-cloak>
                        <x-styles-panel :font-options="$fontOptions" :all-visual-presets="$allVisualPresets" />
                    </div>
                </aside>

                <main class="builder-main">
                    <x-preview-canvas :page-data="$pageData" :preview-config="$previewConfig" />
                    <div class="builder-save-shell">
                        <button type="submit" class="builder-save-btn w-full md:w-auto md:min-w-64" :disabled="isSubmitting" x-text="isSubmitting ? 'Saving...' : 'Save Changes'"></button>
                        <input type="hidden" name="edited_data" :value="serialized">
                    </div>
                </main>

                <aside class="builder-inspector">
                    <x-right-inspector :spacing-scale="$spacingScale" />
                </aside>
            </div>
        </form>

        <div x-show="sectionModalOpen" x-cloak class="cropper-backdrop" @click="sectionModalOpen = false"></div>
        <div x-show="sectionModalOpen" x-cloak class="cropper-modal" @click.stop>
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.24em] text-slate-400">Add Block</p>
                    <h2 class="mt-2 text-2xl font-black text-white">Pilih blok siap pakai</h2>
                </div>
                <button type="button" class="btn-secondary !px-3 !py-2 !text-xs" @click="sectionModalOpen = false">Tutup</button>
            </div>
            <div class="preset-grid mt-5">
                <template x-for="block in sectionRegistry" :key="block.type">
                    <button type="button" class="editor-card text-left transition hover:bg-white/5" @click="createSection(block.type)">
                        <p class="text-sm font-black text-white" x-text="block.label"></p>
                        <p class="mt-2 text-sm leading-7 text-slate-400" x-text="block.description"></p>
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
        function adaptiveBuilder({ initial, projectName, presets, previewConfig, sectionRegistry, uploadEndpoint }) {
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
                leftTab: 'layers',
                workspaceMode: 'visual',
                selectedMeta: 'hero',
                previewMode: 'desktop',
                previewScale: 1,
                previewCanvasHeight: 0,
                previewFrameHeight: 0,
                sectionModalOpen: false,
                cropModal: { open: false, fileName: null, target: null },
                cropper: null,
                selectedIndex: -1,
                selectedPresetKey: initial.theme?.design_style_key ?? initial.theme?.visual_preset_key ?? 'clean-commerce',
                history: [],
                historyIndex: -1,
                copiedSection: null,
                isSubmitting: false,
                codeDraft: '',
                codeDirty: false,
                codeError: '',
                get serialized() {
                    return JSON.stringify({
                        meta: { ...this.meta, project_name: this.projectName },
                        theme: { ...this.theme, visual_preset_key: this.selectedPresetKey || null, design_style_key: this.selectedPresetKey || null },
                        hero: this.hero,
                        sections: this.sections,
                    });
                },
                init() {
                    this.theme = this.normalizeTheme(this.theme ?? {});
                    this.sections = (this.sections || []).map((section) => this.normalizeSection(section));
                    this.codeDraft = this.prettySerialized();
                    this.pushHistory();
                    this.$watch('theme', () => this.syncStateAfterChange());
                    this.$watch('hero', () => this.syncStateAfterChange());
                    this.$watch('sections', () => this.syncStateAfterChange());
                    this.$watch('projectName', () => this.syncStateAfterChange());
                    this.$nextTick(() => {
                        this.syncPreviewScale();
                        this.resizeObserver = new ResizeObserver(() => this.syncPreviewScale());
                        this.resizeObserver.observe(this.$refs.previewHost);
                        this.resizeObserver.observe(this.$refs.previewCanvas);
                        window.addEventListener('resize', this.syncPreviewScale.bind(this));
                    });
                },
                syncStateAfterChange() {
                    this.$nextTick(() => {
                        if (!this.codeDirty) {
                            this.syncCodeDraft();
                        }
                        this.pushHistory();
                        this.syncPreviewScale();
                    });
                },
                setWorkspaceMode(mode) {
                    this.workspaceMode = mode;
                    if (mode === 'code' && !this.codeDirty) {
                        this.syncCodeDraft();
                    }
                    this.$nextTick(() => this.syncPreviewScale());
                },
                prettySerialized() {
                    try {
                        return JSON.stringify(JSON.parse(this.serialized), null, 2);
                    } catch (error) {
                        return this.serialized;
                    }
                },
                syncCodeDraft() {
                    this.codeDraft = this.prettySerialized();
                    this.codeError = '';
                    this.codeDirty = false;
                },
                onCodeInput(value) {
                    this.codeDraft = value;
                    this.codeDirty = true;
                    this.codeError = '';
                },
                applyCodeDraft() {
                    try {
                        const parsed = JSON.parse(this.codeDraft);
                        this.projectName = parsed.meta?.project_name || this.projectName;
                        this.meta = parsed.meta ?? {};
                        this.theme = this.normalizeTheme(parsed.theme ?? {});
                        this.hero = parsed.hero ?? {};
                        this.sections = (parsed.sections ?? []).map((section) => this.normalizeSection(section));
                        this.selectedPresetKey = parsed.theme?.design_style_key ?? parsed.theme?.visual_preset_key ?? this.selectedPresetKey;
                        this.codeDirty = false;
                        this.codeError = '';
                        this.pushHistory();
                        this.$nextTick(() => this.syncPreviewScale());
                    } catch (error) {
                        this.codeError = 'JSON tidak valid. Periksa koma, kutip, atau struktur data.';
                    }
                },
                resetCodeDraft() {
                    this.syncCodeDraft();
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
                        spacing_density: theme.spacing_density ?? 'balanced',
                        heading_scale: theme.heading_scale ?? 'lg',
                    };
                },
                normalizeSection(section) {
                    return {
                        ...section,
                        style: {
                            padding_top: 80,
                            padding_bottom: 80,
                            padding_left: 24,
                            padding_right: 24,
                            margin_top: 0,
                            margin_bottom: 0,
                            section_gap: 24,
                            max_width: 1180,
                            container_mode: 'boxed',
                            text_align: 'left',
                            ...(section.style ?? {}),
                        },
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
                    this.previewFrameHeight = Math.max(560, this.$refs.previewHost.clientHeight - 12);
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
                previewVars() {
                    return `--lp-primary:${this.theme.palette.primary};--lp-secondary:${this.theme.palette.secondary};--lp-accent:${this.theme.palette.accent};--lp-text:${this.theme.palette.text};--lp-background:${this.theme.palette.background};--lp-surface:${this.theme.palette.surface};--lp-muted:${this.theme.palette.muted};--lp-font:${this.theme.font};`;
                },
                sectionWrapStyle(id) {
                    const section = this.sections.find((item) => item.id === id);
                    if (!section) return '';
                    const style = section.style || {};
                    return `--lp-section-pt:${style.padding_top || 80}px;--lp-section-pb:${style.padding_bottom || 80}px;--lp-section-pl:${style.padding_left || 24}px;--lp-section-pr:${style.padding_right || 24}px;--lp-section-mt:${style.margin_top || 0}px;--lp-section-mb:${style.margin_bottom || 0}px;--lp-section-maxw:${style.max_width || 1180}px;--lp-section-gap:${style.section_gap || 24}px;--lp-section-align:${style.text_align || 'left'};`;
                },
                sectionIsSelected(id) {
                    return this.currentSection()?.id === id;
                },
                selectSectionById(id) {
                    const index = this.sections.findIndex((item) => item.id === id);
                    if (index >= 0) {
                        this.selectedIndex = index;
                        this.selectedMeta = null;
                    }
                },
                currentSection() {
                    return this.selectedIndex >= 0 ? this.sections[this.selectedIndex] ?? null : null;
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
                        design_style_key: preset.slug,
                        design_style_label: preset.name,
                    });
                    this.syncCodeDraft();
                    this.pushHistory();
                },
                pushHistory() {
                    const snapshot = JSON.stringify({ theme: this.theme, hero: this.hero, sections: this.sections, projectName: this.projectName, selectedPresetKey: this.selectedPresetKey });
                    if (this.history[this.historyIndex] === snapshot) return;
                    this.history = this.history.slice(0, this.historyIndex + 1);
                    this.history.push(snapshot);
                    this.historyIndex = this.history.length - 1;
                },
                restoreHistory(index) {
                    const snapshot = this.history[index];
                    if (!snapshot) return;
                    const parsed = JSON.parse(snapshot);
                    this.theme = this.normalizeTheme(parsed.theme ?? {});
                    this.hero = parsed.hero ?? {};
                    this.sections = (parsed.sections ?? []).map((section) => this.normalizeSection(section));
                    this.projectName = parsed.projectName ?? this.projectName;
                    this.selectedPresetKey = parsed.selectedPresetKey ?? this.selectedPresetKey;
                },
                undo() {
                    if (this.historyIndex <= 0) return;
                    this.historyIndex -= 1;
                    this.restoreHistory(this.historyIndex);
                },
                redo() {
                    if (this.historyIndex >= this.history.length - 1) return;
                    this.historyIndex += 1;
                    this.restoreHistory(this.historyIndex);
                },
                labelize(value) {
                    return String(value).replaceAll('_', ' ').replace(/\b\w/g, (char) => char.toUpperCase());
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
                setSectionStyle(field, value) {
                    if (!this.currentSection()) return;
                    this.currentSection().style[field] = ['container_mode', 'text_align'].includes(field) ? value : Number(value);
                    this.pushHistory();
                },
                fieldIsLong(field) {
                    return ['description', 'copy', 'subtitle', 'subheadline', 'answer', 'question', 'caption'].includes(field);
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
                    this.openCropperFromInput(event, { target: 'item', itemIndex: index, field: 'image_url' });
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
                moveSection(index, direction) {
                    const target = index + direction;
                    if (target < 0 || target >= this.sections.length) return;
                    const copy = [...this.sections];
                    const [item] = copy.splice(index, 1);
                    copy.splice(target, 0, item);
                    this.sections = copy;
                    this.selectedIndex = target;
                    this.pushHistory();
                },
                duplicateSection(index) {
                    const section = this.sections[index];
                    if (!section) return;
                    const clone = JSON.parse(JSON.stringify(section));
                    clone.id = `${section.type}-${Date.now()}`;
                    this.sections.splice(index + 1, 0, clone);
                    this.selectedIndex = index + 1;
                    this.pushHistory();
                },
                removeSection(index) {
                    if (index < 0 || index >= this.sections.length) return;
                    this.sections.splice(index, 1);
                    this.selectedIndex = this.sections.length ? Math.min(index, this.sections.length - 1) : -1;
                    this.pushHistory();
                },
                toggleSection(index) {
                    this.sections[index].enabled = this.sections[index].enabled === false;
                    this.pushHistory();
                },
                registryItem(type) {
                    return this.sectionRegistry.find((item) => item.type === type) ?? null;
                },
                createSection(type) {
                    const registry = this.registryItem(type);
                    if (!registry) return;
                    const section = this.normalizeSection({
                        id: `${type}-${Date.now()}`,
                        type,
                        enabled: true,
                        content: JSON.parse(JSON.stringify(registry.default_content ?? {})),
                        style: JSON.parse(JSON.stringify(registry.default_style ?? {})),
                    });
                    this.sections.push(section);
                    this.selectedIndex = this.sections.length - 1;
                    this.selectedMeta = null;
                    this.sectionModalOpen = false;
                    this.pushHistory();
                },
                pasteSection() {
                    if (!this.copiedSection) return;
                    const clone = JSON.parse(JSON.stringify(this.copiedSection));
                    clone.id = `${clone.type}-${Date.now()}`;
                    this.sections.push(clone);
                    this.selectedIndex = this.sections.length - 1;
                    this.pushHistory();
                },
                mediaLibrary() {
                    const images = [];
                    if (this.hero.visual_image) images.push({ url: this.hero.visual_image, label: 'Hero Visual' });
                    if (this.hero.logo) images.push({ url: this.hero.logo, label: 'Logo' });
                    this.sections.forEach((section) => {
                        const items = section.content?.items || [];
                        items.forEach((item) => {
                            const url = item.image_url || item.image || null;
                            if (url) images.push({ url, label: section.type });
                        });
                    });
                    return images.filter((item, index, arr) => arr.findIndex((sub) => sub.url === item.url) === index);
                },
                applyMediaToSelected(url) {
                    if (this.selectedMeta === 'hero') {
                        this.hero.visual_image = url;
                        return;
                    }
                    const section = this.currentSection();
                    if (!section) return;
                    if (Array.isArray(section.content.items) && section.content.items.length) {
                        const targetIndex = section.content.items.findIndex((item) => item?.image_url === '' || item?.image === '');
                        const safeIndex = targetIndex >= 0 ? targetIndex : 0;
                        if (section.content.items[safeIndex]?.image_url !== undefined) {
                            section.content.items[safeIndex].image_url = url;
                        } else if (section.content.items[safeIndex]?.image !== undefined) {
                            section.content.items[safeIndex].image = url;
                        }
                    }
                },
                async sendAsset(file, filename = 'upload.png') {
                    const token = document.querySelector('meta[name="csrf-token"]')?.content;
                    const formData = new FormData();
                    formData.append('asset', file, filename);
                    const response = await fetch(this.uploadEndpoint, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
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
                            this.cropper = new window.Cropper(this.$refs.cropperImage, { viewMode: 1, autoCropArea: 1, responsive: true });
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
                    if (this.cropper) { this.cropper.destroy(); this.cropper = null; }
                },
                async confirmCrop() {
                    if (!this.cropper || !this.cropModal.target) return;
                    const canvas = this.cropper.getCroppedCanvas({ imageSmoothingQuality: 'high' });
                    const blob = await new Promise((resolve) => canvas.toBlob(resolve, 'image/png', 0.92));
                    const result = await this.sendAsset(blob, this.cropModal.fileName || 'crop.png');
                    const target = this.cropModal.target;
                    if (target.target === 'hero') this.hero[target.field] = result.url;
                    if (target.target === 'item') this.currentSection().content.items[target.itemIndex][target.field] = result.url;
                    this.pushHistory();
                    this.closeCropper();
                },
                submitEditorForm(event) {
                    if (this.isSubmitting) return;
                    if (this.workspaceMode === 'code' && this.codeDirty) {
                        this.applyCodeDraft();
                        if (this.codeError) return;
                    }
                    this.isSubmitting = true;
                    this.pushHistory();
                    this.$nextTick(() => event.target.submit());
                },
            };
        }
    </script>
@endsection
