@php($isEditor = ($mode ?? 'render') === 'editor')
@php($ref = isset($section['id']) ? "sections.find(item => item.id === '".$section['id']."')" : null)
<section class="lp-section" @if($isEditor) x-show="{{ $ref }}.enabled !== false" :style="sectionInlineStyle('{{ $section['id'] }}')" @click.stop="selectSectionById('{{ $section['id'] }}')" :class="sectionIsSelected('{{ $section['id'] }}') ? 'lp-section-selected' : ''" @endif>
    <div class="lp-container">
        <div class="lp-section-heading">
            <div class="lp-kicker" @if($isEditor) contenteditable="true" @input="{{ $ref }}.content.eyebrow = $event.target.innerText" x-text="{{ $ref }}.content.eyebrow" @endif>{{ data_get($section, 'content.eyebrow') }}</div>
            <h2 class="lp-section-title" @if($isEditor) contenteditable="true" @input="{{ $ref }}.content.title = $event.target.innerText" x-text="{{ $ref }}.content.title" @endif>{{ data_get($section, 'content.title') }}</h2>
        </div>
        <div class="lp-visual-grid">
            @foreach (data_get($section, 'content.items', []) as $item)
                <article class="lp-visual-card">
                    @if (data_get($item, 'image'))
                        <img src="{{ data_get($item, 'image') }}" alt="{{ data_get($item, 'title') }}" class="lp-visual-image" @if($isEditor) :src="{{ $ref }}.content.items[{{ $loop->index }}].image" @endif>
                    @else
                        <div class="lp-visual-image"></div>
                    @endif
                    <h3 class="lp-card-title" @if($isEditor) contenteditable="true" @input="{{ $ref }}.content.items[{{ $loop->index }}].title = $event.target.innerText" x-text="{{ $ref }}.content.items[{{ $loop->index }}].title" @endif>{{ data_get($item, 'title') }}</h3>
                    <p class="lp-card-copy" @if($isEditor) contenteditable="true" @input="{{ $ref }}.content.items[{{ $loop->index }}].caption = $event.target.innerText" x-text="{{ $ref }}.content.items[{{ $loop->index }}].caption" @endif>{{ data_get($item, 'caption') }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>
