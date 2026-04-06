<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class NicheThemeResolver
{
    public function __construct(
        protected VisualPresetResolver $visualPresetResolver,
        protected DesignStyleResolver $designStyleResolver,
    ) {
    }

    public function all(): array
    {
        return config('landing_themes.niches', []);
    }

    public function options(): array
    {
        return collect($this->all())
            ->map(fn (array $theme, string $key) => ['value' => $key, 'label' => $theme['label']])
            ->values()
            ->all();
    }

    public function catalog(): array
    {
        return collect($this->all())
            ->map(function (array $theme, string $key) {
                return [
                    'key' => $key,
                    'label' => $theme['label'],
                    'visual_mood' => $theme['visual_mood'],
                    'copy_tone' => $theme['copy_tone'],
                    'hero_style' => $theme['hero_style'],
                    'palette' => $theme['palette'],
                    'presets' => $this->designStyleResolver->options(),
                ];
            })
            ->all();
    }

    public function visualOptions(): array
    {
        return ['clean', 'premium', 'fun', 'bold', 'elegant', 'playful', 'masculine', 'feminine', 'modern', 'minimal', 'editorial', 'fresh', 'warm', 'natural'];
    }

    public function toneOptions(): array
    {
        return ['formal', 'professional', 'premium', 'soft', 'exciting', 'friendly', 'confident', 'direct'];
    }

    public function businessTypes(): array
    {
        return ['produk', 'jasa', 'digital product', 'toko', 'personal brand'];
    }

    public function label(string $niche): string
    {
        return (string) Arr::get($this->all(), $this->normalizeKey($niche).'.label', 'UMKM Umum');
    }

    public function presetsForNiche(string $niche): array
    {
        return collect($this->visualPresetResolver->options())
            ->map(function (array $preset) use ($niche) {
                $preset['recommended'] = in_array($niche, $preset['recommended_niches'] ?? [], true);

                return $preset;
            })
            ->values()
            ->all();
    }

    protected function applyOverrides(array $theme, array $input): array
    {
        $visual = Str::lower((string) ($input['visual_preference'] ?? ''));
        $tone = Str::lower((string) ($input['tone_copy'] ?? ''));

        if ($visual !== '') {
            $theme['visual_override'] = $visual;
        }

        if ($tone !== '') {
            $theme['copy_tone'] = $this->mergeTone((string) $theme['copy_tone'], $tone);
        }

        if (in_array($visual, ['minimal', 'clean'], true)) {
            $theme['card_radius'] = 'xl';
            $theme['spacing_density'] = 'airy';
        }

        if (in_array($visual, ['bold', 'masculine'], true)) {
            $theme['heading_scale'] = 'xxl';
            $theme['button_style'] = 'sharp';
        }

        if (in_array($visual, ['premium', 'elegant'], true)) {
            $theme['button_style'] = 'luxury';
        }

        if (in_array($visual, ['fun', 'playful'], true)) {
            $theme['card_radius'] = '3xl';
        }

        return $theme;
    }

    protected function mergeTone(string $base, string $override): string
    {
        $parts = collect(explode(',', $base))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->prepend($override)
            ->unique()
            ->values()
            ->all();

        return implode(', ', $parts);
    }

    protected function normalizeKey(string $value): string
    {
        $value = Str::slug($value);

        return array_key_exists($value, $this->all()) ? $value : 'umkm-umum';
    }

    protected function defaultPresetSlugForNiche(string $niche): ?string
    {
        return $this->visualPresetResolver->recommendedForNiche($niche)[0]['slug'] ?? 'clean-commerce';
    }

    public function resolve(array $input): array
    {
        $niche = $this->normalizeKey($input['niche'] ?? 'umkm-umum');
        $default = config('landing_themes.default', []);
        $theme = array_replace_recursive($default, config("landing_themes.niches.$niche", []));
        $presetSlug = $input['design_style'] ?? $input['visual_preset'] ?? $this->defaultPresetSlugForNiche($niche);
        $theme = $this->visualPresetResolver->applyPreset($theme, $presetSlug);

        $theme['key'] = $niche;
        $theme['name'] = $niche.'-'.$theme['visual_mood'];

        if (! empty($input['font_preference'])) {
            $theme['font'] = $input['font_preference'];
        }

        return $this->applyOverrides($theme, $input);
    }
}
