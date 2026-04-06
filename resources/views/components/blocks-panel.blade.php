<div class="builder-panel">
    <div class="builder-panel-header">
        <div>
            <p class="builder-eyebrow">Blocks</p>
            <h3 class="builder-panel-title">Ready Blocks</h3>
        </div>
    </div>
    <div class="builder-block-grid">
        <template x-for="block in sectionRegistry" :key="block.type">
            <button type="button" class="builder-block-card" @click="createSection(block.type)">
                <strong x-text="block.label"></strong>
                <span x-text="block.description"></span>
            </button>
        </template>
    </div>
</div>
