@props([
    'preset' => [],
    'active' => false,
    'recommended' => false,
])

<button
    type="button"
    {{ $attributes->merge(['class' => 'group relative overflow-hidden rounded-[22px] border p-4 text-left transition']) }}
    @class([
        'border-emerald-400/40 bg-emerald-400/10 ring-2 ring-emerald-400/30' => $active,
        'border-white/10 bg-slate-950/40 hover:bg-white/5' => ! $active,
    ])
>
    <div class="flex items-start justify-between gap-3">
        <div>
            <p class="text-sm font-black text-white">{{ $preset['name'] ?? $preset['label'] ?? 'Preset' }}</p>
            <p class="mt-1 text-xs leading-6 text-slate-400">{{ $preset['description'] ?? '' }}</p>
        </div>
        @if ($recommended)
            <span class="preview-chip !text-[10px]">Recommended</span>
        @endif
    </div>

    <div class="mt-4 flex gap-2">
        @foreach (array_slice(array_values($preset['palette'] ?? []), 0, 5) as $color)
            <span class="h-8 w-8 rounded-2xl border border-white/10" style="background: {{ $color }}"></span>
        @endforeach
    </div>
</button>
