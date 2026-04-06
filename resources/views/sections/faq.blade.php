@php($ref = isset($section['id']) ? "sections.find(item => item.id === '".$section['id']."')" : null)
@php($isEditor = ($mode ?? 'render') === 'editor')
<section id="lp-faq" class="lp-section" @if($isEditor) x-show="{{ $ref }}.enabled !== false" :style="'order:' + sectionOrder('{{ $section['id'] }}')" @endif>
    <div class="lp-container">
        <div class="lp-section-heading">
            <div class="lp-kicker" @if($isEditor) contenteditable="true" @input="{{ $ref }}.content.eyebrow = $event.target.innerText" x-text="{{ $ref }}.content.eyebrow" @endif>{{ data_get($section, 'content.eyebrow') }}</div>
            <h2 class="lp-section-title" @if($isEditor) contenteditable="true" @input="{{ $ref }}.content.title = $event.target.innerText" x-text="{{ $ref }}.content.title" @endif>{{ data_get($section, 'content.title') }}</h2>
        </div>
        <div class="lp-faq-list">
            @foreach (data_get($section, 'content.items', []) as $item)
                <article class="lp-faq-item">
                    <h3 class="lp-faq-question"
                        @if($isEditor)
                            contenteditable="true"
                            @input="{{ $ref }}.content.items[{{ $loop->index }}].question = $event.target.innerText"
                            x-text="{{ $ref }}.content.items[{{ $loop->index }}].question"
                        @endif
                    >{{ data_get($item, 'question') }}</h3>
                    <p class="lp-faq-answer"
                        @if($isEditor)
                            contenteditable="true"
                            @input="{{ $ref }}.content.items[{{ $loop->index }}].answer = $event.target.innerText"
                            x-text="{{ $ref }}.content.items[{{ $loop->index }}].answer"
                        @endif
                    >{{ data_get($item, 'answer') }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>
