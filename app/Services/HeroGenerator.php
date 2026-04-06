<?php

namespace App\Services;

use Illuminate\Support\Str;

class HeroGenerator
{
    protected array $stopwords = [
        'dan', 'yang', 'untuk', 'dengan', 'agar', 'lebih', 'bisa', 'buat', 'para', 'kalian',
        'anda', 'supaya', 'dari', 'ke', 'atau', 'serta', 'ini', 'itu', 'jadi',
    ];

    public function generate(array $input, array $theme): array
    {
        $pattern = $this->selectPattern($input, $theme);
        $focus = $this->summarizeValueProposition($input, $theme['key']);
        $headline = match ($pattern) {
            'problem-based' => $this->problemBasedHeadline($input, $focus, $theme['key']),
            'result-based' => $this->resultBasedHeadline($input, $focus, $theme['key']),
            'transformation-based' => $this->transformationBasedHeadline($input, $focus, $theme['key']),
            'offer-based' => $this->offerBasedHeadline($input, $focus, $theme['key']),
            default => $this->trustBasedHeadline($input, $focus, $theme['key']),
        };

        $headline = $this->finalizeHeadline($headline, $input, $theme['key']);

        return [
            'pattern' => $pattern,
            'badge' => $this->badge($input, $theme),
            'headline' => $headline,
            'subheadline' => $this->subheadline($input, $headline, $theme),
            'cta_text' => $input['cta_utama'] ?: 'Konsultasi Sekarang',
            'cta_link' => $input['cta_link'] ?: '#',
            'support_text' => $this->supportText($theme),
            'visual_image' => $input['hero_image_url'] ?? null,
            'logo' => $input['logo_url'] ?? null,
        ];
    }

    public function summarizeValueProposition(array $input, string $niche): string
    {
        $candidate = $this->bestPhrase([
            $this->firstItem($input['manfaat_utama'] ?? ''),
            $this->firstItem($input['keunggulan_kompetitor'] ?? ''),
            $input['nama_produk_layanan'] ?? '',
            $input['deskripsi_singkat'] ?? '',
        ]);

        $candidate = $this->compressPhrase($candidate, 4);

        return match ($niche) {
            'fashion' => $candidate !== '' ? $candidate : 'Look Lebih Standout',
            'skincare' => $candidate !== '' ? $candidate : 'Kulit Tampak Fresh',
            'jasa' => $candidate !== '' ? $candidate : 'Closing Lebih Meyakinkan',
            'travel' => $candidate !== '' ? $candidate : 'Trip Lebih Praktis',
            'helm', 'motor-jual-beli', 'otomotif-bengkel' => $candidate !== '' ? $candidate : 'Performa Lebih Siap',
            default => $candidate !== '' ? $candidate : 'Hasil Lebih Cepat',
        };
    }

    protected function selectPattern(array $input, array $theme): string
    {
        if (($input['copywriting_framework'] ?? null) === 'pas' || ($input['awareness_level'] ?? null) === 'Problem Aware') {
            return 'problem-based';
        }

        if (in_array(($input['goal'] ?? null), ['Waitlist', 'Trial'], true)) {
            return 'offer-based';
        }

        return match ($theme['key']) {
            'fashion' => 'transformation-based',
            'skincare' => 'result-based',
            'jasa', 'kesehatan-klinik' => 'trust-based',
            'travel', 'event-wedding' => 'transformation-based',
            'helm', 'motor-jual-beli', 'otomotif-bengkel', 'gadget-elektronik' => 'offer-based',
            'personal-branding', 'agency-studio-design' => 'trust-based',
            default => filled($input['harga'] ?? null) ? 'offer-based' : 'problem-based',
        };
    }

    protected function problemBasedHeadline(array $input, string $focus, string $niche): string
    {
        $problem = $this->compressPhrase((string) ($input['masalah_utama'] ?? ''), 4);

        if ($problem === '') {
            return 'Solusi Praktis untuk '.$focus;
        }

        return match ($niche) {
            'jasa' => 'Atasi '.$problem.' dengan Jasa Profesional',
            'travel' => 'Liburan Tanpa '.$problem,
            'helm', 'otomotif-bengkel', 'motor-jual-beli' => 'Lawan '.$problem.' dengan Pilihan Tepat',
            default => 'Atasi '.$problem.' Lebih Cepat',
        };
    }

    protected function resultBasedHeadline(array $input, string $focus, string $niche): string
    {
        return match ($niche) {
            'skincare' => $focus.' untuk Glow Lebih Percaya Diri',
            'travel' => 'Trip Lebih Ringan, Momen Lebih Berkesan',
            'edukasi-course' => 'Belajar Lebih Terarah, Progress Lebih Jelas',
            default => $focus.' untuk Hasil Lebih Cepat',
        };
    }

    protected function transformationBasedHeadline(array $input, string $focus, string $niche): string
    {
        return match ($niche) {
            'fashion' => 'Look Lebih Tajam, Persona Lebih Berani',
            'travel' => 'Dari Penat ke Trip yang Lebih Seru',
            'event-wedding' => 'Momen Lebih Indah, Persiapan Lebih Tenang',
            default => 'Dari Ribet ke '.$focus,
        };
    }

    protected function offerBasedHeadline(array $input, string $focus, string $niche): string
    {
        $product = $this->compressPhrase((string) ($input['nama_produk_layanan'] ?? $input['nama_brand_bisnis'] ?? ''), 4);

        return match ($niche) {
            'digital-product' => 'Akses Instan untuk '.$focus,
            'helm' => 'Helm Siap Pakai untuk Ride Lebih Aman',
            'motor-jual-beli' => 'Motor Siap Jalan, Chat dan Deal Lebih Cepat',
            'gadget-elektronik' => 'Upgrade Gadget untuk Performa Lebih Kencang',
            default => ($product !== '' ? $product : 'Penawaran Ini').' untuk '.$focus,
        };
    }

    protected function trustBasedHeadline(array $input, string $focus, string $niche): string
    {
        return match ($niche) {
            'jasa' => 'Jasa Profesional untuk Hasil Lebih Meyakinkan',
            'personal-branding' => 'Bangun Personal Brand yang Terlihat Lebih Kredibel',
            'agency-studio-design' => 'Studio Kreatif untuk Brand yang Lebih Tajam',
            'kesehatan-klinik' => 'Layanan Profesional yang Terasa Lebih Tenang',
            default => 'Pilihan Tepat untuk '.$focus,
        };
    }

    protected function finalizeHeadline(string $headline, array $input, string $niche): string
    {
        $headline = trim(preg_replace('/\s+/', ' ', $headline) ?: $headline);
        $headline = $this->limitWords($headline, 10);

        if (str_word_count($headline) < 4) {
            $headline = match ($niche) {
                'fashion' => 'Style yang Terlihat Lebih Standout',
                'skincare' => 'Kulit Fresh untuk Glow Lebih Percaya Diri',
                'jasa' => 'Jasa Profesional untuk Closing Lebih Cepat',
                'travel' => 'Trip Lebih Ringan untuk Momen Berkesan',
                'helm', 'motor-jual-beli', 'otomotif-bengkel' => 'Pilihan Siap Pakai untuk Performa Lebih Aman',
                default => 'Solusi Siap Pakai untuk Hasil Lebih Cepat',
            };
        }

        return Str::headline($headline);
    }

    protected function subheadline(array $input, string $headline, array $theme): string
    {
        $target = trim((string) ($input['target_market'] ?? 'target market Anda'));
        $description = trim((string) ($input['deskripsi_singkat'] ?? ''));
        $benefit = $this->firstItem((string) ($input['manfaat_utama'] ?? ''));
        $goal = trim((string) ($input['goal'] ?? 'Sales'));
        $traffic = trim((string) ($input['traffic_source'] ?? 'Meta Ads'));
        $sentence = $description !== '' ? $description : ($benefit !== '' ? $benefit : 'Siap membantu bisnis Anda terlihat lebih meyakinkan dan mudah diambil tindakannya.');

        return Str::limit(
            trim(sprintf(
                'Cocok untuk %s yang ingin %s. Disusun untuk goal %s dan traffic %s. %s',
                $target,
                Str::lower($this->compressPhrase($this->bestPhrase([$benefit, $input['masalah_utama'] ?? 'hasil lebih baik']), 5)),
                Str::lower($goal),
                $traffic,
                $sentence
            )),
            180,
            '...'
        );
    }

    protected function badge(array $input, array $theme): string
    {
        return match ($theme['key']) {
            'digital-product' => 'Instant Access',
            'skincare' => 'Best Seller',
            'travel' => 'Open Trip Favorit',
            'helm' => 'Safety Pick',
            'motor-jual-beli' => 'Stok Pilihan',
            'fashion' => 'New Collection',
            'properti' => 'Limited Unit',
            'agency-studio-design' => 'Creative Partner',
            default => Str::headline((string) ($input['business_type'] ?? 'Adaptive Template')),
        };
    }

    protected function supportText(array $theme): string
    {
        return implode(' • ', array_slice($theme['trust_elements'] ?? [], 0, 3));
    }

    protected function compressPhrase(string $text, int $maxWords = 4): string
    {
        $text = Str::of($text)
            ->replaceMatches('/[^\pL\pN\s-]+/u', ' ')
            ->squish()
            ->lower()
            ->value();

        if ($text === '') {
            return '';
        }

        $words = collect(preg_split('/\s+/', $text) ?: [])
            ->filter()
            ->reject(fn ($word) => in_array($word, $this->stopwords, true))
            ->take($maxWords)
            ->map(fn ($word) => Str::headline($word))
            ->values()
            ->all();

        return implode(' ', $words);
    }

    protected function limitWords(string $text, int $limit): string
    {
        $words = preg_split('/\s+/', trim($text)) ?: [];

        return implode(' ', array_slice($words, 0, $limit));
    }

    protected function firstItem(string $text): string
    {
        return trim((string) collect(preg_split('/\r\n|\r|\n|,/', $text) ?: [])->filter()->first());
    }

    protected function bestPhrase(array $candidates): string
    {
        foreach ($candidates as $candidate) {
            if (trim((string) $candidate) !== '') {
                return (string) $candidate;
            }
        }

        return '';
    }
}
