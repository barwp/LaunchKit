<?php

namespace App\Services;

class EditorStateService
{
    public function fonts(): array
    {
        return config('visual_identity.fonts', []);
    }

    public function spacingScale(): array
    {
        return [
            '0', '8', '12', '16', '20', '24', '32', '40', '56', '72', '96',
        ];
    }

    public function sectionDefaults(): array
    {
        return [
            'padding_top' => 80,
            'padding_bottom' => 80,
            'padding_left' => 24,
            'padding_right' => 24,
            'margin_top' => 0,
            'margin_bottom' => 0,
            'max_width' => 1180,
            'container_mode' => 'boxed',
            'text_align' => 'left',
            'section_gap' => 24,
        ];
    }
}
