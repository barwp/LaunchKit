@php($ref = isset($section['id']) ? "sections.find(item => item.id === '".$section['id']."')" : null)
@php($isEditor = ($mode ?? 'render') === 'editor')
<section class="lp-section" @if($isEditor) x-show="{{ $ref }}.enabled !== false" :style="'order:' + sectionOrder('{{ $section['id'] }}')" @endif>
    <div class="lp-container">
        <div class="lp-panel" style="text-align:center;">
            <div class="lp-kicker" @if($isEditor) contenteditable="true" @input="{{ $ref }}.content.eyebrow = $event.target.innerText" x-text="{{ $ref }}.content.eyebrow" @endif>{{ data_get($section, 'content.eyebrow') }}</div>
            <h2 class="lp-section-title" style="max-width:820px; margin-inline:auto;"
                @if($isEditor)
                    contenteditable="true"
                    @input="{{ $ref }}.content.title = $event.target.innerText"
                    x-text="{{ $ref }}.content.title"
                @endif
            >{{ data_get($section, 'content.title') }}</h2>
            <p class="lp-copy" style="max-width:700px; margin:18px auto 0;"
                @if($isEditor)
                    contenteditable="true"
                    @input="{{ $ref }}.content.description = $event.target.innerText"
                    x-text="{{ $ref }}.content.description"
                @endif
            >{{ data_get($section, 'content.description') }}</p>
            <div class="lp-button-row" style="justify-content:center;">
                <a class="lp-button lp-button-primary"
                    @if($isEditor)
                        :href="{{ $ref }}.content.link"
                        contenteditable="true"
                        @input="{{ $ref }}.content.button = $event.target.innerText"
                        x-text="{{ $ref }}.content.button"
                    @else
                        href="{{ data_get($section, 'content.link', '#') }}"
                    @endif
                >{{ data_get($section, 'content.button', 'Hubungi Sekarang') }}</a>
            </div>
        </div>
    </div>
</section>
