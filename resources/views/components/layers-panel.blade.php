<div class="builder-panel">
    <div class="builder-panel-header">
        <div>
            <p class="builder-eyebrow">Layers</p>
            <h3 class="builder-panel-title">Hierarchy</h3>
        </div>
    </div>
    <div class="builder-stack">
        <button type="button" class="builder-layer" :class="selectedMeta === 'hero' ? 'is-active' : ''" @click="selectedMeta = 'hero'; selectedIndex = -1">
            <span>Hero</span>
            <small x-text="hero.headline"></small>
        </button>

        <template x-for="(section, index) in sections" :key="section.id">
            <button type="button" class="builder-layer" :class="selectedIndex === index ? 'is-active' : ''" @click="selectedIndex = index; selectedMeta = null">
                <span x-text="section.content.title || section.content.eyebrow || labelize(section.type)"></span>
                <small x-text="labelize(section.type)"></small>
            </button>
        </template>
    </div>
</div>
