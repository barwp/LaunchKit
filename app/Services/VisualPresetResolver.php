<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class VisualPresetResolver
{
    public function __construct(
        protected DesignStyleResolver $designStyleResolver,
    ) {
    }

    public function all(): array
    {
        return $this->designStyleResolver->all();
    }

    public function options(): array
    {
        return collect($this->all())
            ->map(fn (array $preset) => [
                'name' => $preset['name'],
                'slug' => $preset['slug'],
                'description' => $preset['description'],
                'palette' => $preset['palette'],
                'recommended_niches' => $preset['recommended_niches'] ?? [],
            ])
            ->values()
            ->all();
    }

    public function find(string $slug): ?array
    {
        $normalized = Str::slug($slug);

        return collect($this->all())
            ->first(fn (array $preset) => ($preset['slug'] ?? null) === $normalized);
    }

    public function recommendedForNiche(string $niche): array
    {
        return collect($this->all())
            ->filter(fn (array $preset) => in_array($niche, $preset['recommended_niches'] ?? [], true))
            ->values()
            ->all();
    }

    public function applyPreset(array $theme, ?string $slug): array
    {
        $theme = $this->designStyleResolver->applyToTheme($theme, $slug);
        $theme['visual_preset_key'] = $theme['design_style_key'] ?? $theme['visual_preset_key'] ?? null;
        $theme['visual_preset_label'] = $theme['design_style_label'] ?? $theme['visual_preset_label'] ?? null;

        return $theme;
    }
}
