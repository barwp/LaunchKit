@php
    $theme = $pageData['theme'] ?? [];
    $palette = $theme['palette'] ?? [];
    $mode = $mode ?? 'render';
@endphp
<div
    @if ($mode === 'editor')
        :class="'lp-page is-editor hero-' + theme.hero_style + ' button-' + theme.button_style + ' pattern-' + theme.background_pattern + ' spacing-' + theme.spacing_density + ' scale-' + (theme.heading_scale || 'lg')"
        :style="previewVars()"
    @else
        class="lp-page hero-{{ $theme['hero_style'] ?? 'saas-split' }} button-{{ $theme['button_style'] ?? 'rounded-pill' }} pattern-{{ $theme['background_pattern'] ?? 'grid-glow' }} spacing-{{ $theme['spacing_density'] ?? 'normal' }} scale-{{ $theme['heading_scale'] ?? 'lg' }}"
        style="
            --lp-primary: {{ $palette['primary'] ?? '#10b981' }};
            --lp-secondary: {{ $palette['secondary'] ?? '#d1fae5' }};
            --lp-accent: {{ $palette['accent'] ?? '#34d399' }};
            --lp-text: {{ $palette['text'] ?? '#ecfdf5' }};
            --lp-background: {{ $palette['background'] ?? '#07110f' }};
            --lp-surface: {{ $palette['surface'] ?? '#0c1714' }};
            --lp-muted: {{ $palette['muted'] ?? 'rgba(209, 250, 229, 0.72)' }};
            --lp-font: {{ $theme['font'] ?? 'Plus Jakarta Sans' }};
        "
    @endif
>
    <div
        class="lp-node-wrap lp-node-hero"
        @if ($mode === 'editor')
            @click.stop="selectedMeta = 'hero'; selectedIndex = -1"
            :class="selectedMeta === 'hero' ? 'lp-section-selected' : ''"
        @endif
    >
        @include('sections.hero', [
            'hero' => $pageData['hero'] ?? [],
            'theme' => $theme,
            'mode' => $mode,
        ])
    </div>

    @foreach (($pageData['sections'] ?? []) as $section)
        @if (($section['enabled'] ?? true) !== false)
            <div
                class="lp-node-wrap"
                @if ($mode === 'editor')
                    :style="sectionWrapStyle('{{ $section['id'] }}')"
                    @click.stop="selectSectionById('{{ $section['id'] }}')"
                    :class="sectionIsSelected('{{ $section['id'] }}') ? 'lp-section-selected' : ''"
                @else
                    style="
                        --lp-section-pt: {{ data_get($section, 'style.padding_top', 80) }}px;
                        --lp-section-pb: {{ data_get($section, 'style.padding_bottom', 80) }}px;
                        --lp-section-pl: {{ data_get($section, 'style.padding_left', 24) }}px;
                        --lp-section-pr: {{ data_get($section, 'style.padding_right', 24) }}px;
                        --lp-section-mt: {{ data_get($section, 'style.margin_top', 0) }}px;
                        --lp-section-mb: {{ data_get($section, 'style.margin_bottom', 0) }}px;
                        --lp-section-maxw: {{ data_get($section, 'style.max_width', 1180) }}px;
                        --lp-section-gap: {{ data_get($section, 'style.section_gap', 24) }}px;
                        --lp-section-align: {{ data_get($section, 'style.text_align', 'left') }};
                    "
                @endif
            >
                @includeIf('sections.' . $section['type'], [
                    'section' => $section,
                    'theme' => $theme,
                    'mode' => $mode,
                ])
            </div>
        @endif
    @endforeach
</div>
