@props([
    'mode' => 'desktop',
    'previewConfig' => [],
])

@php
    $desktop = $previewConfig['desktop'] ?? ['frame_height' => 820];
    $mobile = $previewConfig['mobile'] ?? ['frame_height' => 860];
@endphp

<div class="preview-shell" :class="previewMode === 'mobile' ? 'is-mobile' : 'is-desktop'">
    <div class="preview-browser" x-show="previewMode !== 'mobile'">
        <div class="preview-browser-bar">
            <span></span><span></span><span></span>
        </div>
    </div>

    <div class="preview-device" x-show="previewMode === 'mobile'">
        <div class="preview-device-notch"></div>
    </div>

    <div class="preview-viewport"
        :style="previewViewportStyle()">
        {{ $slot }}
    </div>
</div>
