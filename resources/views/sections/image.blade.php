@php($isEditor = ($mode ?? 'render') === 'editor')
@php($ref = isset($section['id']) ? "sections.find(item => item.id === '".$section['id']."')" : null)
@php($layout = data_get($section, 'content.layout', 'grid-3'))

<section class="lp-section" @if($isEditor) x-show="{{ $ref }}.enabled !== false" :style="'order:' + sectionOrder('{{ $section['id'] }}')" @endif>
    <div class="lp-container">
        <div class="lp-section-heading">
            <div class="lp-kicker" @if($isEditor) contenteditable="true" @input="{{ $ref }}.content.eyebrow = $event.target.innerText" x-text="{{ $ref }}.content.eyebrow" @endif>{{ data_get($section, 'content.eyebrow', 'Gallery') }}</div>
            <h2 class="lp-section-title" @if($isEditor) contenteditable="true" @input="{{ $ref }}.content.title = $event.target.innerText" x-text="{{ $ref }}.content.title" @endif>{{ data_get($section, 'content.title') }}</h2>
            <p class="lp-copy" @if($isEditor) contenteditable="true" @input="{{ $ref }}.content.subtitle = $event.target.innerText" x-text="{{ $ref }}.content.subtitle" @endif>{{ data_get($section, 'content.subtitle') }}</p>
        </div>

        <div class="lp-image-gallery lp-image-{{ $layout }}">
            @foreach (data_get($section, 'content.items', []) as $item)
                <article class="lp-visual-card">
                    @if (data_get($item, 'image_url'))
                        <img
                            src="{{ data_get($item, 'image_url') }}"
                            alt="{{ data_get($item, 'caption', 'Gallery image') }}"
                            class="lp-visual-image"
                            @if($isEditor) :src="{{ $ref }}.content.items[{{ $loop->index }}].image_url" @endif
                        >
                    @else
                        <div class="lp-visual-image"></div>
                    @endif
                    <p class="lp-card-copy"
                        @if($isEditor)
                            contenteditable="true"
                            @input="{{ $ref }}.content.items[{{ $loop->index }}].caption = $event.target.innerText"
                            x-text="{{ $ref }}.content.items[{{ $loop->index }}].caption"
                        @endif
                    >{{ data_get($item, 'caption') }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>
