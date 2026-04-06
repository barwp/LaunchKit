@php($ref = isset($section['id']) ? "sections.find(item => item.id === '".$section['id']."')" : null)
@php($isEditor = ($mode ?? 'render') === 'editor')
<section class="lp-section" @if($isEditor) x-show="{{ $ref }}.enabled !== false" :style="'order:' + sectionOrder('{{ $section['id'] }}')" @endif>
    <div class="lp-container">
        <div class="lp-section-heading">
            <div class="lp-kicker" @if($isEditor) contenteditable="true" @input="{{ $ref }}.content.eyebrow = $event.target.innerText" x-text="{{ $ref }}.content.eyebrow" @endif>{{ data_get($section, 'content.eyebrow') }}</div>
            <h2 class="lp-section-title" @if($isEditor) contenteditable="true" @input="{{ $ref }}.content.title = $event.target.innerText" x-text="{{ $ref }}.content.title" @endif>{{ data_get($section, 'content.title') }}</h2>
        </div>
        <div class="lp-card-grid">
            @foreach (data_get($section, 'content.items', []) as $item)
                <article class="lp-card">
                    <div class="lp-avatar" @if($isEditor) x-text="({{ $ref }}.content.items[{{ $loop->index }}].name || 'AA').slice(0,2).toUpperCase()" @endif>{{ strtoupper(substr(data_get($item, 'name', 'AA'), 0, 2)) }}</div>
                    <p class="lp-quote"
                        @if($isEditor)
                            contenteditable="true"
                            @input="{{ $ref }}.content.items[{{ $loop->index }}].quote = $event.target.innerText"
                            x-text="{{ $ref }}.content.items[{{ $loop->index }}].quote"
                        @endif
                    >{{ data_get($item, 'quote') }}</p>
                    <p class="lp-card-copy" style="color: var(--lp-text); font-weight: 800;"
                        @if($isEditor)
                            contenteditable="true"
                            @input="{{ $ref }}.content.items[{{ $loop->index }}].name = $event.target.innerText"
                            x-text="{{ $ref }}.content.items[{{ $loop->index }}].name"
                        @endif
                    >{{ data_get($item, 'name') }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>
