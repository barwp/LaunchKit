@php($ref = isset($section['id']) ? "sections.find(item => item.id === '".$section['id']."')" : null)
@php($isEditor = ($mode ?? 'render') === 'editor')
<section id="lp-pricing" class="lp-section" @if($isEditor) x-show="{{ $ref }}.enabled !== false" :style="'order:' + sectionOrder('{{ $section['id'] }}')" @endif>
    <div class="lp-container">
        <div class="lp-section-heading" style="text-align:center; margin-inline:auto;">
            <div class="lp-kicker" @if($isEditor) contenteditable="true" @input="{{ $ref }}.content.eyebrow = $event.target.innerText" x-text="{{ $ref }}.content.eyebrow" @endif>{{ data_get($section, 'content.eyebrow') }}</div>
            <h2 class="lp-section-title" @if($isEditor) contenteditable="true" @input="{{ $ref }}.content.title = $event.target.innerText" x-text="{{ $ref }}.content.title" @endif>{{ data_get($section, 'content.title') }}</h2>
        </div>
        <div class="lp-pricing-wrap">
            <div class="lp-price-card">
                <p class="lp-copy" style="margin-top:0; text-align:center;">Penawaran utama yang paling mudah dipilih.</p>
                <p class="lp-price" style="text-align:center;"
                    @if($isEditor)
                        contenteditable="true"
                        @input="{{ $ref }}.content.price = $event.target.innerText"
                        x-text="{{ $ref }}.content.price"
                    @endif
                >{{ data_get($section, 'content.price') }}</p>
                <ul class="lp-checklist">
                    @foreach (data_get($section, 'content.items', []) as $item)
                        <li
                            @if($isEditor)
                                contenteditable="true"
                                @input="if (typeof {{ $ref }}.content.items[{{ $loop->index }}] === 'object') { {{ $ref }}.content.items[{{ $loop->index }}].copy = $event.target.innerText } else { {{ $ref }}.content.items[{{ $loop->index }}] = $event.target.innerText }"
                                x-text="typeof {{ $ref }}.content.items[{{ $loop->index }}] === 'object' ? {{ $ref }}.content.items[{{ $loop->index }}].copy : {{ $ref }}.content.items[{{ $loop->index }}]"
                            @endif
                        >{{ is_array($item) ? data_get($item, 'copy', '') : $item }}</li>
                    @endforeach
                </ul>
                <div class="lp-button-row" style="justify-content:center;">
                    <a class="lp-button lp-button-primary"
                        @if($isEditor)
                            :href="{{ $ref }}.content.cta_link"
                            contenteditable="true"
                            @input="{{ $ref }}.content.cta_text = $event.target.innerText"
                            x-text="{{ $ref }}.content.cta_text"
                        @else
                            href="{{ data_get($section, 'content.cta_link', '#') }}"
                        @endif
                    >{{ data_get($section, 'content.cta_text', 'Ambil Penawaran') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>
