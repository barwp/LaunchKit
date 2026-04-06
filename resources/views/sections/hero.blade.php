@php($isEditor = ($mode ?? 'render') === 'editor')
<section id="lp-hero" class="lp-section">
    <div class="lp-topbar">
        <div class="lp-marquee">
            @for ($i = 0; $i < 6; $i++)
                <span>{{ strtoupper(data_get($hero, 'cta_text', 'Ambil Penawaran')) }}</span>
                <span>{{ strtoupper(data_get($hero, 'badge', 'Adaptive Hero')) }}</span>
                <span>{{ strtoupper(data_get($theme, 'label', 'LaunchKit Adaptive')) }}</span>
            @endfor
        </div>
    </div>

    <div class="lp-navbar-shell">
        <div class="lp-container">
            <div class="lp-navbar">
                <a href="#lp-hero" class="lp-brand">
                    @if (($isEditor && data_get($hero, 'logo')) || (!$isEditor && data_get($hero, 'logo')))
                        <img
                            src="{{ data_get($hero, 'logo') }}"
                            alt="{{ data_get($theme, 'label', 'LaunchKit Adaptive') }}"
                            class="lp-brand-logo"
                            @if ($isEditor)
                                :src="hero.logo || '{{ data_get($hero, 'logo') }}'"
                            @endif
                        >
                    @else
                        <span class="lp-brand-mark">{{ str(data_get($theme, 'label', 'LA'))->substr(0, 2)->upper() }}</span>
                    @endif
                    <span class="lp-brand-text">{{ data_get($theme, 'label', 'LaunchKit Adaptive') }}</span>
                </a>

                <nav class="lp-nav-links" aria-label="Primary navigation">
                    <a href="#lp-features">Fitur</a>
                    <a href="#lp-benefits">Benefit</a>
                    <a href="#lp-pricing">Harga</a>
                    <a href="#lp-faq">FAQ</a>
                </nav>

                <a href="{{ data_get($hero, 'cta_link', '#') }}" class="lp-nav-cta">{{ data_get($hero, 'cta_text', 'Hubungi Sekarang') }}</a>

                <details class="lp-mobile-nav">
                    <summary class="lp-nav-toggle" aria-label="Toggle navigation">
                        <span></span>
                        <span></span>
                        <span></span>
                    </summary>
                    <div class="lp-mobile-menu">
                        <a href="#lp-features">Fitur</a>
                        <a href="#lp-benefits">Benefit</a>
                        <a href="#lp-pricing">Harga</a>
                        <a href="#lp-faq">FAQ</a>
                        <a href="{{ data_get($hero, 'cta_link', '#') }}" class="is-cta">{{ data_get($hero, 'cta_text', 'Hubungi Sekarang') }}</a>
                    </div>
                </details>
            </div>
        </div>
    </div>

    <div class="lp-container">
        <div class="lp-grid-hero">
            <div>
                @if ($isEditor)
                    <div class="mb-5" x-show="hero.logo">
                        <img
                            src="{{ data_get($hero, 'logo') }}"
                            alt="{{ data_get($theme, 'label', 'Brand Logo') }}"
                            class="h-10 w-auto rounded-xl bg-white/90 p-2"
                            :src="hero.logo || '{{ data_get($hero, 'logo') }}'"
                        >
                    </div>
                @elseif (data_get($hero, 'logo'))
                    <div class="mb-5">
                        <img
                            src="{{ data_get($hero, 'logo') }}"
                            alt="{{ data_get($theme, 'label', 'Brand Logo') }}"
                            class="h-10 w-auto rounded-xl bg-white/90 p-2"
                        >
                    </div>
                @endif

                <div class="lp-kicker"
                    @if ($isEditor)
                        contenteditable="true"
                        @input="hero.badge = $event.target.innerText"
                        x-text="hero.badge"
                    @endif
                >{{ data_get($hero, 'badge') }}</div>

                <h1 class="lp-title">
                    <span class="lp-highlight"
                        @if ($isEditor)
                            contenteditable="true"
                            @input="hero.headline = $event.target.innerText"
                            x-text="hero.headline"
                        @endif
                    >{{ data_get($hero, 'headline') }}</span>
                </h1>

                <p class="lp-subtitle"
                    @if ($isEditor)
                        contenteditable="true"
                        @input="hero.subheadline = $event.target.innerText"
                        x-text="hero.subheadline"
                    @endif
                >{{ data_get($hero, 'subheadline') }}</p>

                <div class="lp-button-row">
                    <a class="lp-button lp-button-primary"
                        @if ($isEditor)
                            :href="hero.cta_link"
                            contenteditable="true"
                            @input="hero.cta_text = $event.target.innerText"
                            x-text="hero.cta_text"
                        @else
                            href="{{ data_get($hero, 'cta_link', '#') }}"
                        @endif
                    >{{ data_get($hero, 'cta_text') }}</a>

                    <span class="lp-button lp-button-secondary"
                        @if ($isEditor)
                            x-text="hero.pattern"
                        @endif
                    >{{ data_get($hero, 'pattern') }}</span>
                </div>

                <div class="lp-trust">
                    @foreach (array_slice(data_get($theme, 'trust_elements', []), 0, 3) as $trust)
                        <span>{{ $trust }}</span>
                    @endforeach
                </div>
            </div>

            <div class="lp-hero-visual">
                <div class="lp-device">
                    @if ($isEditor)
                        <div class="lp-device-screen" :class="hero.visual_image ? 'has-image' : ''">
                            <img
                                src="{{ data_get($hero, 'visual_image') }}"
                                alt="{{ data_get($theme, 'label', 'Adaptive Preview') }}"
                                class="lp-device-image"
                                x-show="hero.visual_image"
                                :src="hero.visual_image || '{{ data_get($hero, 'visual_image') }}'"
                            >
                            <div x-show="!hero.visual_image">
                                <p class="lp-device-title">
                                    {{ data_get($theme, 'label', 'Adaptive') }}
                                    <small>{{ data_get($theme, 'hero_style', 'adaptive') }}</small>
                                </p>

                                <div class="lp-spec-grid">
                                    @foreach (array_slice(data_get($theme, 'trust_elements', []), 0, 3) as $item)
                                        <div class="lp-spec">{{ $item }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @elseif (data_get($hero, 'visual_image'))
                        <div class="lp-device-screen has-image">
                            <img
                                src="{{ data_get($hero, 'visual_image') }}"
                                alt="{{ data_get($theme, 'label', 'Adaptive Preview') }}"
                                class="lp-device-image"
                            >
                        </div>
                    @else
                        <div class="lp-device-screen">
                            <p class="lp-device-title">
                                {{ data_get($theme, 'label', 'Adaptive') }}
                                <small>{{ data_get($theme, 'hero_style', 'adaptive') }}</small>
                            </p>

                            <div class="lp-spec-grid">
                                @foreach (array_slice(data_get($theme, 'trust_elements', []), 0, 3) as $item)
                                    <div class="lp-spec">{{ $item }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="lp-floating-tag one">{{ data_get($theme, 'button_style', 'CTA') }}</div>
                    <div class="lp-floating-tag two">{{ data_get($theme, 'background_pattern', 'Pattern') }}</div>
                </div>
            </div>
        </div>
    </div>
</section>
