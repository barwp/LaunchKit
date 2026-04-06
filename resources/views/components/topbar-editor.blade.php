<div class="builder-topbar">
    <div class="builder-topbar-group">
        <button type="button" class="builder-tab" :class="leftTab === 'layers' ? 'is-active' : ''" @click="leftTab = 'layers'">Editor</button>
        <button type="button" class="builder-tab" :class="leftTab === 'layers' ? 'is-active' : ''" @click="leftTab = 'layers'">Layers</button>
        <button type="button" class="builder-tab" :class="leftTab === 'styles' ? 'is-active' : ''" @click="leftTab = 'styles'">Styles</button>
        <button type="button" class="builder-tab" :class="workspaceMode === 'visual' ? 'is-active' : ''" @click="setWorkspaceMode('visual')">Visual</button>
        <button type="button" class="builder-tab" :class="workspaceMode === 'code' ? 'is-active' : ''" @click="setWorkspaceMode('code')">Code</button>
    </div>

    <div class="builder-topbar-group">
        <button type="button" class="builder-chip" :class="previewMode === 'desktop' ? 'is-active' : ''" @click="setPreviewMode('desktop')">Desktop</button>
        <button type="button" class="builder-chip" :class="previewMode === 'tablet' ? 'is-active' : ''" @click="setPreviewMode('tablet')">Tablet</button>
        <button type="button" class="builder-chip" :class="previewMode === 'mobile' ? 'is-active' : ''" @click="setPreviewMode('mobile')">Mobile</button>
    </div>

    <div class="builder-topbar-group">
        <button type="button" class="builder-icon-btn" @click="undo()" :disabled="historyIndex <= 0">Undo</button>
        <button type="button" class="builder-icon-btn" @click="redo()" :disabled="historyIndex >= history.length - 1">Redo</button>
        <a href="{{ route('projects.export', $project) }}" class="builder-icon-btn">Export</a>
        <button type="button" class="builder-accent-btn" @click="sectionModalOpen = true">Add Block</button>
        <button type="button" class="builder-icon-btn" @click="pasteSection()">Paste</button>
    </div>
</div>
