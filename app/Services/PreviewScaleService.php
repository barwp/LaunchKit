<?php

namespace App\Services;

class PreviewScaleService
{
    public function config(): array
    {
        return [
            'desktop' => [
                'canvas_width' => 1280,
                'canvas_height' => 900,
                'frame_width' => 1280,
                'frame_height' => 820,
            ],
            'mobile' => [
                'canvas_width' => 390,
                'canvas_height' => 844,
                'frame_width' => 430,
                'frame_height' => 860,
            ],
            'tablet' => [
                'canvas_width' => 834,
                'canvas_height' => 1112,
                'frame_width' => 900,
                'frame_height' => 880,
            ],
        ];
    }
}
