<?php

namespace App\Services;

use Illuminate\Support\Str;

class DesignStyleResolver
{
    public function all(): array
    {
        return config('design_styles.styles', []);
    }

    public function options(): array
    {
        return collect($this->all())->values()->all();
    }

    public function find(?string $slug): ?array
    {
        $slug = Str::slug((string) $slug);

        return collect($this->all())->first(fn (array $style) => ($style['slug'] ?? null) === $slug);
    }

    public function applyToTheme(array $theme, ?string $slug): array
    {
        $style = $this->find($slug);

        if (! $style) {
            return $theme;
        }

        $theme['palette'] = array_replace($theme['palette'] ?? [], $style['palette'] ?? []);
        $theme['font'] = $style['font_preset'] ?? ($theme['font'] ?? 'Plus Jakarta Sans');
        $theme['button_style'] = $style['button_style'] ?? ($theme['button_style'] ?? 'rounded-lg');
        $theme['hero_style'] = $style['hero_style'] ?? ($theme['hero_style'] ?? 'simple-convert');
        $theme['background_pattern'] = $style['background_pattern'] ?? ($theme['background_pattern'] ?? 'soft-grid');
        $theme['spacing_density'] = $style['section_spacing'] ?? ($theme['spacing_density'] ?? 'normal');
        $theme['card_radius'] = $style['radius'] ?? ($theme['card_radius'] ?? '2xl');
        $theme['card_style'] = $style['card_style'] ?? ($theme['card_style'] ?? null);
        $theme['shadow_style'] = $style['shadow_style'] ?? ($theme['shadow_style'] ?? null);
        $theme['badge_style'] = $style['badge_style'] ?? ($theme['badge_style'] ?? null);
        $theme['design_style_key'] = $style['slug'] ?? null;
        $theme['design_style_label'] = $style['name'] ?? null;

        return $theme;
    }
}
