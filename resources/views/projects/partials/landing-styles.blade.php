<style>
    :root {
        --lp-primary: var(--primary, #10b981);
        --lp-secondary: var(--secondary, #d1fae5);
        --lp-accent: var(--accent, #34d399);
        --lp-text: var(--text, #ecfdf5);
        --lp-background: var(--background, #07110f);
        --lp-surface: var(--surface, #0c1714);
        --lp-muted: var(--muted, rgba(209, 250, 229, 0.72));
        --lp-font: var(--font, "Plus Jakarta Sans");
        --lp-border: color-mix(in srgb, var(--lp-primary) 24%, rgba(255, 255, 255, 0.12));
        --lp-shadow: 0 24px 80px rgba(2, 6, 23, 0.35);
    }

    .lp-page {
        position: relative;
        overflow: hidden;
        background: var(--lp-background);
        color: var(--lp-text);
        font-family: var(--lp-font), ui-sans-serif, system-ui, sans-serif;
        width: 100%;
    }

    .lp-editor-frame {
        margin: 0 auto;
        transition: width 180ms ease;
    }

    .lp-editor-frame.is-desktop {
        width: min(100%, 1220px);
    }

    .lp-editor-frame.is-mobile {
        width: min(100%, 430px);
    }

    .lp-page * {
        box-sizing: border-box;
    }

    .lp-page img {
        max-width: 100%;
        height: auto;
    }

    .lp-page a {
        color: inherit;
        text-decoration: none;
    }

    .lp-page::before {
        content: "";
        position: absolute;
        inset: 0;
        pointer-events: none;
        opacity: 0.48;
    }

    .lp-page.pattern-grid-glow::before,
    .lp-page.pattern-grid-industrial::before,
    .lp-page.pattern-soft-grid::before,
    .lp-page.pattern-grid-subtle::before {
        background-image:
            linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
        background-size: 44px 44px;
    }

    .lp-page.pattern-soft-blur::before,
    .lp-page.pattern-organic-wash::before,
    .lp-page.pattern-soft-pattern::before {
        background:
            radial-gradient(circle at 20% 20%, color-mix(in srgb, var(--lp-primary) 22%, transparent), transparent 26%),
            radial-gradient(circle at 80% 10%, color-mix(in srgb, var(--lp-accent) 22%, transparent), transparent 22%),
            radial-gradient(circle at 70% 80%, color-mix(in srgb, var(--lp-secondary) 44%, transparent), transparent 26%);
    }

    .lp-page.pattern-sunset-fade::before,
    .lp-page.pattern-gradient::before {
        background:
            radial-gradient(circle at 15% 10%, rgba(251, 146, 60, 0.26), transparent 18%),
            radial-gradient(circle at 80% 12%, rgba(14, 165, 233, 0.25), transparent 20%);
    }

    .lp-page.pattern-editorial-noise::before,
    .lp-page.pattern-prestige-lines::before,
    .lp-page.pattern-linen::before {
        background:
            linear-gradient(135deg, rgba(255, 255, 255, 0.03), transparent 42%),
            linear-gradient(180deg, rgba(255, 255, 255, 0.03), transparent 55%);
    }

    .lp-page.pattern-noise-texture::before {
        background:
            linear-gradient(135deg, rgba(255, 255, 255, 0.02), transparent 42%),
            linear-gradient(180deg, rgba(255, 255, 255, 0.04), transparent 55%);
        opacity: 0.82;
    }

    .lp-page.pattern-minimal-flat::before,
    .lp-page.pattern-light-clean::before {
        background: none;
    }

    .lp-section {
        position: relative;
        padding: 80px 24px;
    }

    .lp-node-wrap {
        position: relative;
    }

    .lp-node-wrap > .lp-section {
        padding-top: var(--lp-section-pt, 80px);
        padding-bottom: var(--lp-section-pb, 80px);
        padding-left: var(--lp-section-pl, 24px);
        padding-right: var(--lp-section-pr, 24px);
        margin-top: var(--lp-section-mt, 0);
        margin-bottom: var(--lp-section-mb, 0);
    }

    .lp-node-wrap > .lp-section .lp-container {
        width: min(var(--lp-section-maxw, 1180px), calc(100% - 32px));
    }

    .lp-node-wrap > .lp-section .lp-section-heading {
        gap: var(--lp-section-gap, 24px);
    }

    .lp-node-wrap.lp-section-selected::after {
        content: "";
        position: absolute;
        inset: 8px;
        border: 1.5px solid rgba(168, 85, 247, 0.7);
        border-radius: 28px;
        pointer-events: none;
        box-shadow: 0 0 0 1px rgba(168, 85, 247, 0.25), 0 0 30px rgba(168, 85, 247, 0.12);
    }

    .lp-page.spacing-compact .lp-section {
        padding: 60px 24px;
    }

    .lp-page.spacing-structured .lp-section,
    .lp-page.spacing-comfortable .lp-section {
        padding: 92px 24px;
    }

    .lp-page.spacing-airy .lp-section {
        padding: 96px 24px;
    }

    .lp-page.spacing-balanced .lp-section {
        padding: 80px 24px;
    }

    .lp-page.spacing-spacious .lp-section {
        padding: 108px 28px;
    }

    .lp-page.spacing-luxury .lp-section {
        padding: 124px 32px;
    }

    .lp-container {
        position: relative;
        z-index: 1;
        width: min(1180px, calc(100% - 32px));
        margin: 0 auto;
    }

    .lp-topbar {
        overflow: hidden;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        background: linear-gradient(90deg, var(--lp-primary), color-mix(in srgb, var(--lp-primary) 55%, var(--lp-accent) 45%));
        color: #fff;
        position: relative;
        z-index: 0;
    }

    .lp-marquee {
        display: flex;
        align-items: center;
        gap: 26px;
        min-width: max-content;
        padding: 10px 24px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.22em;
        line-height: 1;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .lp-topbar + .lp-container {
        padding-top: 48px;
    }

    .lp-navbar-shell {
        position: sticky;
        top: 0;
        z-index: 14;
        padding: 14px 0 0;
        pointer-events: none;
    }

    .lp-navbar-shell .lp-container {
        pointer-events: auto;
    }

    .lp-navbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 14px 18px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        background: color-mix(in srgb, var(--lp-background) 76%, rgba(2, 6, 23, 0.82));
        backdrop-filter: blur(20px);
        box-shadow: 0 20px 60px rgba(2, 6, 23, 0.24);
    }

    .lp-brand {
        display: inline-flex;
        min-width: 0;
        align-items: center;
        gap: 12px;
        font-weight: 900;
        color: var(--lp-text);
    }

    .lp-brand-logo,
    .lp-brand-mark {
        width: 42px;
        height: 42px;
        flex: none;
        border-radius: 14px;
    }

    .lp-brand-logo {
        object-fit: cover;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.92);
        padding: 6px;
    }

    .lp-brand-mark {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(180deg, color-mix(in srgb, var(--lp-primary) 92%, white 8%), color-mix(in srgb, var(--lp-primary) 72%, black 28%));
        color: color-mix(in srgb, var(--lp-background) 78%, #020617 22%);
        font-size: 14px;
        letter-spacing: 0.08em;
    }

    .lp-brand-text {
        min-width: 0;
        font-size: 15px;
        line-height: 1.2;
        letter-spacing: -0.02em;
        text-wrap: pretty;
    }

    .lp-nav-links {
        display: inline-flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-width: 0;
    }

    .lp-nav-links a,
    .lp-mobile-menu a {
        padding: 11px 14px;
        border-radius: 14px;
        color: var(--lp-muted);
        font-size: 13px;
        font-weight: 800;
        transition: background 180ms ease, color 180ms ease, transform 180ms ease;
    }

    .lp-nav-links a:hover,
    .lp-mobile-menu a:hover {
        background: rgba(255, 255, 255, 0.06);
        color: var(--lp-text);
    }

    .lp-nav-cta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 48px;
        padding: 0 18px;
        flex: none;
        border-radius: 16px;
        background: linear-gradient(180deg, color-mix(in srgb, var(--lp-primary) 92%, white 8%), color-mix(in srgb, var(--lp-primary) 78%, black 22%));
        color: color-mix(in srgb, var(--lp-background) 70%, #020617 30%);
        font-size: 13px;
        font-weight: 900;
        letter-spacing: 0.03em;
        box-shadow: 0 16px 40px color-mix(in srgb, var(--lp-primary) 28%, transparent);
    }

    .lp-mobile-nav {
        display: none;
        position: relative;
        flex: none;
    }

    .lp-mobile-nav summary {
        list-style: none;
    }

    .lp-mobile-nav summary::-webkit-details-marker {
        display: none;
    }

    .lp-nav-toggle {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        width: 48px;
        height: 48px;
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.04);
        cursor: pointer;
    }

    .lp-nav-toggle span {
        width: 18px;
        height: 2px;
        border-radius: 999px;
        background: var(--lp-text);
        transition: transform 180ms ease, opacity 180ms ease;
    }

    .lp-mobile-nav[open] .lp-nav-toggle span:nth-child(1) {
        transform: translateY(6px) rotate(45deg);
    }

    .lp-mobile-nav[open] .lp-nav-toggle span:nth-child(2) {
        opacity: 0;
    }

    .lp-mobile-nav[open] .lp-nav-toggle span:nth-child(3) {
        transform: translateY(-6px) rotate(-45deg);
    }

    .lp-mobile-menu {
        position: absolute;
        right: 0;
        top: calc(100% + 12px);
        display: grid;
        gap: 8px;
        width: min(320px, calc(100vw - 32px));
        padding: 12px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 22px;
        background: color-mix(in srgb, var(--lp-background) 88%, rgba(2, 6, 23, 0.94));
        box-shadow: 0 20px 60px rgba(2, 6, 23, 0.32);
        backdrop-filter: blur(18px);
    }

    .lp-mobile-menu .is-cta {
        margin-top: 4px;
        justify-content: center;
        background: color-mix(in srgb, var(--lp-primary) 16%, transparent);
        color: var(--lp-text);
    }

    .lp-kicker {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 9px 14px;
        border-radius: 999px;
        border: 1px solid color-mix(in srgb, var(--lp-primary) 26%, rgba(255, 255, 255, 0.1));
        background: color-mix(in srgb, var(--lp-secondary) 68%, transparent);
        color: color-mix(in srgb, var(--lp-primary) 90%, var(--lp-text) 10%);
        font-size: 11px;
        font-weight: 900;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        position: relative;
        z-index: 2;
    }

    .lp-kicker::before {
        content: "";
        width: 7px;
        height: 7px;
        border-radius: 999px;
        background: var(--lp-primary);
        box-shadow: 0 0 14px var(--lp-primary);
    }

    .lp-grid-hero {
        display: grid;
        gap: 46px;
        align-items: center;
        grid-template-columns: minmax(0, 1.05fr) minmax(320px, 0.95fr);
        position: relative;
        z-index: 1;
    }

    .lp-grid-hero > div:first-child {
        padding-top: 8px;
    }

    .lp-page.hero-editorial .lp-grid-hero,
    .lp-page.hero-story-gallery .lp-grid-hero,
    .lp-page.hero-destination-banner .lp-grid-hero {
        grid-template-columns: 1fr;
    }

    .lp-page.hero-trust-stack .lp-grid-hero,
    .lp-page.hero-simple-convert .lp-grid-hero,
    .lp-page.hero-clinic-trust .lp-grid-hero {
        grid-template-columns: minmax(0, 1.25fr) minmax(280px, 0.75fr);
    }

    .lp-title {
        margin: 24px 0 0;
        font-size: clamp(2.8rem, 7vw, 5.8rem);
        line-height: 0.94;
        font-weight: 900;
        letter-spacing: -0.06em;
        color: var(--lp-text);
        text-wrap: balance;
        overflow-wrap: anywhere;
    }

    .lp-page.scale-lg .lp-title {
        font-size: clamp(2.8rem, 7vw, 5.8rem);
    }

    .lp-page.scale-xl .lp-title {
        font-size: clamp(3.2rem, 7.5vw, 6.6rem);
    }

    .lp-page.scale-xxl .lp-title {
        font-size: clamp(3.8rem, 8vw, 7.8rem);
    }

    .lp-page.hero-editorial .lp-title,
    .lp-page.hero-authority-profile .lp-title,
    .lp-page.hero-prestige-estate .lp-title {
        line-height: 1;
        letter-spacing: -0.04em;
    }

    .lp-highlight {
        color: var(--lp-primary);
        text-shadow: 0 0 28px color-mix(in srgb, var(--lp-primary) 36%, transparent);
    }

    .lp-subtitle,
    .lp-copy {
        color: var(--lp-muted);
        line-height: 1.8;
    }

    .lp-subtitle {
        margin-top: 16px;
        max-width: 720px;
        font-size: clamp(1rem, 1.6vw, 1.18rem);
    }

    .lp-copy {
        font-size: 1rem;
    }

    .lp-button-row {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        margin-top: 26px;
    }

    .lp-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 56px;
        padding: 0 24px;
        border-radius: 16px;
        font-size: 14px;
        font-weight: 900;
        letter-spacing: 0.03em;
        transition: transform 160ms ease, box-shadow 160ms ease;
    }

    .lp-page.button-rounded-pill .lp-button {
        border-radius: 999px;
    }

    .lp-page.button-sharp .lp-button,
    .lp-page.button-editorial .lp-button {
        border-radius: 10px;
    }

    .lp-button-primary {
        background: linear-gradient(180deg, color-mix(in srgb, var(--lp-primary) 92%, white 8%), color-mix(in srgb, var(--lp-primary) 78%, black 22%));
        color: color-mix(in srgb, var(--lp-background) 70%, #020617 30%);
        box-shadow: 0 16px 40px color-mix(in srgb, var(--lp-primary) 28%, transparent);
    }

    .lp-button-secondary {
        border: 1px solid rgba(255, 255, 255, 0.14);
        background: rgba(255, 255, 255, 0.05);
        color: var(--lp-text);
    }

    .lp-panel,
    .lp-card,
    .lp-faq-item,
    .lp-device,
    .lp-visual-card {
        border: 1px solid var(--lp-border);
        background: color-mix(in srgb, var(--lp-surface) 88%, transparent);
        border-radius: 28px;
        box-shadow: var(--lp-shadow);
        backdrop-filter: blur(16px);
    }

    .lp-page.button-sharp .lp-panel,
    .lp-page.button-sharp .lp-card,
    .lp-page.button-sharp .lp-faq-item {
        border-radius: 22px;
    }

    .lp-page.button-editorial .lp-panel,
    .lp-page.button-editorial .lp-card,
    .lp-page.button-editorial .lp-faq-item {
        border-radius: 18px;
    }

    .lp-panel,
    .lp-card,
    .lp-faq-item,
    .lp-visual-card {
        padding: 28px;
    }

    .lp-card-grid,
    .lp-stat-row,
    .lp-two-col,
    .lp-visual-grid,
    .lp-process-grid {
        display: grid;
        gap: 18px;
        margin-top: 28px;
    }

    .lp-card-grid {
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    }

    .lp-two-col,
    .lp-process-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .lp-stat-row {
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    }

    .lp-visual-grid {
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    }

    .lp-image-gallery {
        display: grid;
        gap: 18px;
        margin-top: 28px;
    }

    .lp-image-grid-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .lp-image-grid-3 {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .lp-image-masonry-simple {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .lp-image-masonry-simple .lp-visual-card:nth-child(2n) .lp-visual-image {
        aspect-ratio: 3 / 4;
    }

    .lp-image-slider-style-preview {
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    }

    .lp-image-collage-clean {
        grid-template-columns: 1.2fr 0.8fr 0.8fr;
    }

    .lp-card-number,
    .lp-visual-tag,
    .lp-avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 44px;
        min-height: 44px;
        border-radius: 14px;
        background: color-mix(in srgb, var(--lp-primary) 16%, transparent);
        color: var(--lp-primary);
        font-size: 13px;
        font-weight: 900;
        letter-spacing: 0.16em;
        text-transform: uppercase;
    }

    .lp-avatar {
        border-radius: 999px;
    }

    .lp-card-title,
    .lp-faq-question,
    .lp-section-title {
        color: var(--lp-text);
    }

    .lp-card-title {
        margin: 16px 0 0;
        font-size: 1.15rem;
        line-height: 1.35;
        font-weight: 800;
    }

    .lp-card-copy,
    .lp-faq-answer,
    .lp-quote,
    .lp-stat-label {
        margin-top: 12px;
        color: var(--lp-muted);
        line-height: 1.75;
    }

    .lp-section-heading {
        max-width: 820px;
    }

    .lp-section-title {
        margin: 18px 0 0;
        font-size: clamp(2rem, 4vw, 3.3rem);
        line-height: 1.06;
        font-weight: 900;
        letter-spacing: -0.05em;
        text-wrap: balance;
        overflow-wrap: anywhere;
    }

    .lp-stat-value {
        font-size: clamp(1.8rem, 4vw, 2.6rem);
        line-height: 1;
        font-weight: 900;
        color: var(--lp-primary);
    }

    .lp-trust {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        margin-top: 18px;
        color: var(--lp-muted);
        font-size: 13px;
    }

    .lp-trust span::before {
        content: "•";
        margin-right: 8px;
        color: var(--lp-primary);
    }

    .lp-hero-visual {
        position: relative;
        min-height: 520px;
        display: grid;
        place-items: center;
    }

    .lp-device {
        width: min(100%, 350px);
        padding: 18px;
        position: relative;
    }

    .lp-device-screen {
        min-height: 500px;
        border-radius: 24px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        padding: 72px 22px 24px;
        background:
            linear-gradient(180deg, color-mix(in srgb, var(--lp-background) 86%, black 14%), color-mix(in srgb, var(--lp-surface) 76%, black 24%)),
            radial-gradient(circle at top center, color-mix(in srgb, var(--lp-primary) 16%, transparent), transparent 42%);
        text-align: center;
    }

    .lp-device-screen.has-image {
        padding: 16px;
        overflow: hidden;
    }

    .lp-device-image {
        width: 100%;
        height: 468px;
        object-fit: cover;
        border-radius: 18px;
        display: block;
    }

    .lp-device-title {
        margin: 0;
        color: var(--lp-text);
        font-size: 2.6rem;
        font-weight: 900;
        line-height: 1;
    }

    .lp-device-title small {
        display: block;
        margin-top: 8px;
        color: var(--lp-primary);
        font-size: 0.75rem;
        letter-spacing: 0.22em;
        text-transform: uppercase;
    }

    .lp-spec-grid {
        display: grid;
        gap: 12px;
        margin-top: 28px;
    }

    .lp-spec {
        padding: 14px 16px;
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: var(--lp-text);
        font-size: 13px;
        font-weight: 700;
    }

    .lp-floating-tag {
        position: absolute;
        padding: 8px 12px;
        border-radius: 10px;
        background: var(--lp-primary);
        color: color-mix(in srgb, var(--lp-background) 68%, #020617 32%);
        font-size: 10px;
        font-weight: 900;
        letter-spacing: 0.16em;
        text-transform: uppercase;
    }

    .lp-floating-tag.one {
        top: 20%;
        right: 2%;
        transform: rotate(10deg);
    }

    .lp-floating-tag.two {
        bottom: 28%;
        left: -2%;
        transform: rotate(-7deg);
    }

    .lp-checklist {
        display: grid;
        gap: 12px;
        margin: 24px 0 0;
        padding: 0;
        list-style: none;
    }

    .lp-checklist li {
        display: flex;
        gap: 12px;
        color: var(--lp-text);
        line-height: 1.7;
    }

    .lp-checklist li::before {
        content: "✓";
        color: var(--lp-primary);
        font-weight: 900;
    }

    .lp-pricing-wrap {
        display: grid;
        gap: 24px;
        margin-top: 28px;
    }

    .lp-price-card {
        padding: 34px;
        border: 1px solid color-mix(in srgb, var(--lp-primary) 34%, rgba(255,255,255,0.12));
        border-radius: 32px;
        background:
            linear-gradient(180deg, color-mix(in srgb, var(--lp-surface) 92%, transparent), color-mix(in srgb, var(--lp-background) 78%, transparent)),
            radial-gradient(circle at top center, color-mix(in srgb, var(--lp-primary) 18%, transparent), transparent 44%);
        box-shadow: 0 24px 80px color-mix(in srgb, var(--lp-primary) 10%, rgba(2,6,23,0.9));
    }

    .lp-price {
        margin: 12px 0 0;
        color: var(--lp-primary);
        font-size: clamp(2.8rem, 6vw, 4.5rem);
        line-height: 1;
        font-weight: 900;
        letter-spacing: -0.06em;
    }

    .lp-old-price {
        margin-top: 10px;
        color: color-mix(in srgb, var(--lp-muted) 65%, transparent);
        text-decoration: line-through;
    }

    .lp-meta-row {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        margin-top: 18px;
        color: var(--lp-muted);
        font-size: 13px;
    }

    .lp-faq-list {
        display: grid;
        gap: 16px;
        margin-top: 28px;
    }

    .lp-visual-image {
        width: 100%;
        aspect-ratio: 4 / 3;
        border-radius: 22px;
        object-fit: cover;
        background:
            linear-gradient(180deg, color-mix(in srgb, var(--lp-primary) 18%, transparent), transparent),
            linear-gradient(135deg, color-mix(in srgb, var(--lp-secondary) 70%, transparent), color-mix(in srgb, var(--lp-surface) 74%, transparent));
    }

    .lp-before-after {
        display: grid;
        gap: 16px;
        margin-top: 28px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .lp-before-card {
        padding: 28px;
        border-radius: 28px;
        border: 1px solid var(--lp-border);
        background: color-mix(in srgb, var(--lp-surface) 88%, transparent);
    }

    .lp-before-card.before .lp-card-number {
        color: #fb7185;
        background: rgba(251, 113, 133, 0.14);
    }

    .lp-before-card.after .lp-card-number {
        color: var(--lp-primary);
    }

    .lp-editor-note {
        position: sticky;
        bottom: 14px;
        z-index: 2;
        display: flex;
        justify-content: center;
        padding-top: 14px;
    }

    .lp-editor-note span {
        padding: 10px 16px;
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,0.08);
        background: rgba(2, 6, 23, 0.72);
        color: var(--lp-muted);
        font-size: 11px;
        letter-spacing: 0.14em;
        text-transform: uppercase;
    }

    [contenteditable="true"] {
        outline: none;
        transition: box-shadow 120ms ease, background 120ms ease;
    }

    [contenteditable="true"]:focus {
        border-radius: 10px;
        background: rgba(255,255,255,0.05);
        box-shadow: 0 0 0 2px color-mix(in srgb, var(--lp-primary) 40%, transparent);
    }

    @media (max-width: 960px) {
        .lp-grid-hero,
        .lp-two-col,
        .lp-process-grid,
        .lp-before-after,
        .lp-image-grid-2,
        .lp-image-grid-3,
        .lp-image-masonry-simple,
        .lp-image-collage-clean {
            grid-template-columns: 1fr;
        }

        .lp-hero-visual {
            min-height: auto;
        }

        .lp-device {
            width: min(100%, 320px);
        }

        .lp-device-screen {
            min-height: 420px;
        }
    }

    @media (max-width: 1024px) {
        .lp-navbar {
            gap: 12px;
            padding: 12px 14px;
        }

        .lp-brand-text {
            font-size: 14px;
        }

        .lp-nav-links {
            gap: 4px;
        }

        .lp-nav-links a {
            padding: 10px 12px;
            font-size: 12px;
        }

        .lp-nav-cta {
            min-height: 44px;
            padding: 0 16px;
            font-size: 12px;
        }
    }

    @media (max-width: 768px) {
        .lp-section {
            padding: 58px 18px;
        }

        .lp-container {
            width: min(1180px, calc(100% - 20px));
        }

        .lp-title {
            font-size: clamp(2.2rem, 13vw, 4rem);
        }

        .lp-section-title {
            font-size: clamp(1.7rem, 9vw, 2.7rem);
        }

        .lp-panel,
        .lp-card,
        .lp-faq-item,
        .lp-visual-card,
        .lp-price-card,
        .lp-before-card {
            padding: 22px;
            border-radius: 22px;
        }

        .lp-button-row {
            gap: 12px;
        }

        .lp-button-row .lp-button {
            width: 100%;
        }

        .lp-card-grid,
        .lp-stat-row,
        .lp-visual-grid,
        .lp-pricing-wrap,
        .lp-image-slider-style-preview {
            grid-template-columns: 1fr;
        }

        .lp-floating-tag {
            display: none;
        }

        .lp-navbar-shell {
            padding-top: 10px;
        }

        .lp-navbar {
            padding: 12px 14px;
            gap: 12px;
        }

        .lp-brand-logo,
        .lp-brand-mark {
            width: 38px;
            height: 38px;
            border-radius: 12px;
        }

        .lp-brand-text {
            font-size: 14px;
        }

        .lp-nav-links,
        .lp-nav-cta {
            display: none;
        }

        .lp-mobile-nav {
            display: block;
        }
    }

    @media (max-width: 560px) {
        .lp-topbar + .lp-container {
            padding-top: 32px;
        }

        .lp-marquee {
            gap: 18px;
            padding: 8px 16px;
            font-size: 10px;
        }

        .lp-kicker {
            padding: 8px 12px;
            font-size: 10px;
            letter-spacing: 0.14em;
        }

        .lp-subtitle,
        .lp-copy,
        .lp-card-copy,
        .lp-faq-answer,
        .lp-quote,
        .lp-stat-label {
            line-height: 1.65;
        }

        .lp-navbar {
            padding: 10px 12px;
            border-radius: 20px;
        }

        .lp-brand {
            gap: 10px;
            max-width: calc(100% - 64px);
        }

        .lp-brand-text {
            font-size: 13px;
        }

        .lp-mobile-menu {
            width: min(280px, calc(100vw - 28px));
        }
    }
</style>
