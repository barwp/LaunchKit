@php($isEditor = ($mode ?? 'render') === 'editor')
@php($ref = isset($section['id']) ? "sections.find(item => item.id === '".$section['id']."')" : null)
<section class="lp-section" @if($isEditor) x-show="{{ $ref }}.enabled !== false" :style="'order:' + sectionOrder('{{ $section['id'] }}')" @endif>
    <div class="lp-container">
        <div class="lp-section-heading">
            <div class="lp-kicker" @if($isEditor) contenteditable="true" @input="{{ $ref }}.content.eyebrow = $event.target.innerText" x-text="{{ $ref }}.content.eyebrow" @endif>{{ data_get($section, 'content.eyebrow') }}</div>
            <h2 class="lp-section-title" @if($isEditor) contenteditable="true" @input="{{ $ref }}.content.title = $event.target.innerText" x-text="{{ $ref }}.content.title" @endif>{{ data_get($section, 'content.title') }}</h2>
        </div>
        <div class="lp-process-grid">
            @foreach (data_get($section, 'content.items', []) as $item)
                <article class="lp-card">
                    <div class="lp-card-number"
                        @if($isEditor)
                            contenteditable="true"
                            @input="{{ $ref }}.content.items[{{ $loop->index }}].day = $event.target.innerText"
                            x-text="{{ $ref }}.content.items[{{ $loop->index }}].day"
                        @endif
                    >{{ data_get($item, 'day') }}</div>
                    <h3 class="lp-card-title"
                        @if($isEditor)
                            contenteditable="true"
                            @input="{{ $ref }}.content.items[{{ $loop->index }}].title = $event.target.innerText"
                            x-text="{{ $ref }}.content.items[{{ $loop->index }}].title"
                        @endif
                    >{{ data_get($item, 'title') }}</h3>
                    <p class="lp-card-copy"
                        @if($isEditor)
                            contenteditable="true"
                            @input="{{ $ref }}.content.items[{{ $loop->index }}].copy = $event.target.innerText"
                            x-text="{{ $ref }}.content.items[{{ $loop->index }}].copy"
                        @endif
                    >{{ data_get($item, 'copy') }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>
