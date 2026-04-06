<?php

namespace App\Services;

class LandingPageGenerator
{
    public function __construct(
        protected NicheThemeResolver $themeResolver,
        protected HeroGenerator $heroGenerator,
        protected SectionComposer $sectionComposer,
        protected CopyFrameworkResolver $copyFrameworkResolver,
        protected AudienceResolver $audienceResolver,
    ) {
    }

    public function generate(array $input): array
    {
        $theme = $this->themeResolver->resolve($input);
        $copyFramework = $this->copyFrameworkResolver->resolve($input);
        $hero = $this->heroGenerator->generate($input, $theme);
        $sections = $this->sectionComposer->compose($input, $theme, $hero);

        return [
            'meta' => [
                'project_name' => $input['nama_project'] ?? $input['nama_produk_layanan'] ?? 'LaunchKit Adaptive Project',
                'brand_name' => $input['nama_brand_bisnis'] ?? '',
                'niche' => $theme['key'],
                'niche_label' => $theme['label'],
                'business_type' => $input['business_type'] ?? 'produk',
                'visual_preference' => $input['visual_preference'] ?? '',
                'tone_copy' => $input['tone_copy'] ?? '',
                'platform_target' => $input['platform_target'] ?? '',
                'traffic_source' => $input['traffic_source'] ?? '',
                'goal' => $input['goal'] ?? '',
                'awareness_level' => $input['awareness_level'] ?? '',
                'language_tone' => $input['language_tone'] ?? '',
                'copy_framework' => $copyFramework['slug'] ?? '',
                'copy_framework_label' => $copyFramework['name'] ?? '',
                'target_audience' => $this->audienceResolver->normalize($input['target_audience'] ?? []),
                'target_audience_label' => $this->audienceResolver->summary($input['target_audience'] ?? []),
                'pain_point_audience' => $input['pain_point_audience'] ?? '',
                'desire_goal_audience' => $input['desire_goal_audience'] ?? '',
                'objection_audience' => $input['objection_audience'] ?? '',
                'brand_color_family' => $input['brand_color_family'] ?? '',
                'background_mode' => $input['background_mode'] ?? '',
            ],
            'theme' => [
                'name' => $theme['name'],
                'label' => $theme['label'],
                'palette' => $theme['palette'],
                'font' => $theme['font'],
                'radius' => $theme['card_radius'],
                'button_style' => $theme['button_style'],
                'hero_style' => $theme['hero_style'],
                'layout_style' => $theme['layout_style'],
                'heading_scale' => $theme['heading_scale'],
                'spacing_density' => $theme['spacing_density'],
                'background_pattern' => $theme['background_pattern'],
                'copy_tone' => $theme['copy_tone'],
                'cta_style' => $theme['cta_style'],
                'trust_elements' => $theme['trust_elements'],
                'icon_style' => $theme['icon_style'],
                'visual_preset_key' => $theme['visual_preset_key'] ?? null,
                'visual_preset_label' => $theme['visual_preset_label'] ?? null,
                'card_style' => $theme['card_style'] ?? null,
                'shadow_style' => $theme['shadow_style'] ?? null,
                'badge_style' => $theme['badge_style'] ?? null,
                'design_style_key' => $theme['design_style_key'] ?? null,
                'design_style_label' => $theme['design_style_label'] ?? null,
            ],
            'hero' => $hero,
            'sections' => $sections,
        ];
    }
}
