<div class="builder-canvas-shell">
    <div class="builder-canvas-header">
        <div>
            <p class="builder-eyebrow" x-text="workspaceMode === 'code' ? 'Preview + Code' : 'Live Preview'"></p>
            <h3 class="builder-panel-title" x-text="workspaceMode === 'code' ? 'Code Workspace' : 'Visual Canvas'"></h3>
        </div>
        <div class="builder-topbar-group">
            <span class="builder-chip" x-text="theme.hero_style"></span>
            <span class="builder-chip" x-text="selectedPresetKey"></span>
        </div>
    </div>

    <div class="builder-canvas-workspace" :class="workspaceMode === 'code' ? 'is-code' : 'is-visual'">
        <div x-ref="previewHost" class="builder-canvas-body">
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

        <div class="builder-code-panel" x-show="workspaceMode === 'code'" x-cloak>
            <div class="builder-code-header">
                <div>
                    <p class="builder-eyebrow">Code Editor</p>
                    <h4 class="builder-side-title">JSON State</h4>
                </div>
                <div class="builder-topbar-group">
                    <button type="button" class="builder-icon-btn" @click="resetCodeDraft()">Reset</button>
                    <button type="button" class="builder-accent-btn" @click="applyCodeDraft()">Apply</button>
                </div>
            </div>
            <p class="builder-code-help">Edit state builder dalam format JSON. Setelah valid, preview akan langsung ikut berubah.</p>
            <textarea class="builder-code-editor" :value="codeDraft" @input="onCodeInput($event.target.value)"></textarea>
            <p class="builder-code-error" x-show="codeError" x-text="codeError"></p>
        </div>
    </div>
</div>
