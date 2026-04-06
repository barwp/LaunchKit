<div class="builder-panel">
    <div class="builder-panel-header">
        <div>
            <p class="builder-eyebrow">Styles</p>
            <h3 class="builder-panel-title">Global Visual</h3>
        </div>
    </div>

    <div class="builder-field-grid">
        <label class="builder-field">
            <span>Primary</span>
            <input type="color" x-model="theme.palette.primary">
        </label>
        <label class="builder-field">
            <span>Secondary</span>
            <input type="color" x-model="theme.palette.secondary">
        </label>
        <label class="builder-field">
            <span>Accent</span>
            <input type="color" x-model="theme.palette.accent">
        </label>
        <label class="builder-field">
            <span>Background</span>
            <input type="color" x-model="theme.palette.background">
        </label>
        <label class="builder-field">
            <span>Surface</span>
            <input type="color" x-model="theme.palette.surface">
        </label>
        <label class="builder-field">
            <span>Text</span>
            <input type="color" x-model="theme.palette.text">
        </label>
    </div>

    <div class="builder-stack">
        <label class="builder-field">
            <span>Heading / Body Font</span>
            <select x-model="theme.font">
                @foreach ($fontOptions as $font)
                    <option value="{{ $font }}">{{ $font }}</option>
                @endforeach
            </select>
        </label>
        <label class="builder-field">
            <span>Design Style</span>
            <select x-model="selectedPresetKey" @change="applyPreset(selectedPresetKey)">
                @foreach ($allVisualPresets as $preset)
                    <option value="{{ $preset['slug'] }}">{{ $preset['name'] }}</option>
                @endforeach
            </select>
        </label>
        <label class="builder-field">
            <span>Hero Style</span>
            <input x-model="theme.hero_style">
        </label>
        <label class="builder-field">
            <span>Button Style</span>
            <input x-model="theme.button_style">
        </label>
        <label class="builder-field">
            <span>Background Pattern</span>
            <input x-model="theme.background_pattern">
        </label>
        <label class="builder-field">
            <span>Spacing Preset</span>
            <select x-model="theme.spacing_density">
                <option value="compact">Compact</option>
                <option value="balanced">Balanced</option>
                <option value="spacious">Spacious</option>
                <option value="luxury">Luxury</option>
            </select>
        </label>
    </div>
</div>
