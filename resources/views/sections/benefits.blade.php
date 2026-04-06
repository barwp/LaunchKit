@php($ref = isset($section['id']) ? "sections.find(item => item.id === '".$section['id']."')" : null)
@php($isEditor = ($mode ?? 'render') === 'editor')
<section id="lp-benefits" class="lp-section" @if($isEditor) x-show="{{ $ref }}.enabled !== false" :style="'order:' + sectionOrder('{{ $section['id'] }}')" @endif>
    <div class="lp-container">
        <div class="lp-section-heading">
            <div class="lp-kicker" @if($isEditor) contenteditable="true" @input="{{ $ref }}.content.eyebrow = $event.target.innerText" x-text="{{ $ref }}.content.eyebrow" @endif>{{ data_get($section, 'content.eyebrow') }}</div>
            <h2 class="lp-section-title" @if($isEditor) contenteditable="true" @input="{{ $ref }}.content.title = $event.target.innerText" x-text="{{ $ref }}.content.title" @endif>{{ data_get($section, 'content.title') }}</h2>
        </div>
        <div class="lp-stat-row">
            @foreach (data_get($section, 'content.items', []) as $index => $item)
                <article class="lp-panel">
                    <div class="lp-stat-value">0{{ $index + 1 }}</div>
                    <p class="lp-stat-label"
                        @if($isEditor)
                            contenteditable="true"
                            @input="{{ $ref }}.content.items[{{ $index }}] = $event.target.innerText"
                            x-text="{{ $ref }}.content.items[{{ $index }}]"
                        @endif
                    >{{ $item }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>
