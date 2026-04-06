<?php

namespace App\Services;

use Illuminate\Support\Str;

class SectionComposer
{
    public function registry(): array
    {
        return config('sections.registry', []);
    }

    public function compose(array $input, array $theme, array $hero): array
    {
        $sections = [];

        foreach ($theme['section_order'] as $type) {
            $sections[] = $this->makeSection($type, $input, $theme, $hero);
        }

        return $sections;
    }

    public function makeSection(string $type, array $input = [], array $theme = [], array $hero = []): array
    {
        $registry = $this->registry()[$type] ?? [];

        return [
            'id' => $type.'-'.Str::random(6),
            'type' => $type,
            'enabled' => true,
            'content' => $this->contentFor($type, $input, $theme, $hero),
            'style' => array_merge([
                'card_radius' => $theme['card_radius'] ?? '2xl',
                'spacing_density' => $theme['spacing_density'] ?? 'normal',
                'icon_style' => $theme['icon_style'] ?? 'soft-glow',
                'padding_top' => 80,
                'padding_bottom' => 80,
                'padding_left' => 24,
                'padding_right' => 24,
                'margin_top' => 0,
                'margin_bottom' => 0,
                'section_gap' => 24,
                'max_width' => 1180,
                'container_mode' => 'boxed',
                'text_align' => 'left',
            ], $registry['default_style'] ?? []),
        ];
    }

    protected function contentFor(string $type, array $input, array $theme, array $hero): array
    {
        return match ($type) {
            'stats' => $this->statsContent($input, $theme),
            'trust_badges' => $this->trustBadgesContent($theme),
            'benefits' => $this->benefitsContent($input, $theme),
            'features' => $this->featuresContent($input, $theme),
            'testimonials' => $this->testimonialsContent($input, $theme),
            'pricing' => $this->pricingContent($input, $theme),
            'gallery' => $this->galleryContent($input, $theme),
            'faq' => $this->faqContent($input, $theme),
            'cta' => $this->ctaContent($input, $theme, $hero),
            'process' => $this->processContent($input, $theme),
            'comparison' => $this->comparisonContent($input, $theme),
            'package' => $this->packageContent($input, $theme),
            'before_after' => $this->beforeAfterContent($input, $theme),
            'itinerary' => $this->itineraryContent($input, $theme),
            'image' => $this->imageContent($input, $theme),
            'offer_stack' => $this->offerStackContent($input, $theme),
            'portfolio' => $this->portfolioContent($input, $theme),
            'guarantee' => $this->guaranteeContent($input, $theme),
            default => data_get($this->registry(), $type.'.default_content', ['title' => Str::headline($type), 'items' => []]),
        };
    }

    protected function statsContent(array $input, array $theme): array
    {
        $benefits = $this->splitLines((string) ($input['manfaat_utama'] ?? ''));
        $features = $this->splitLines((string) ($input['fitur_utama'] ?? ''));

        return [
            'eyebrow' => 'Highlight Cepat',
            'title' => 'Kenapa pilihan ini terasa lebih siap dipakai',
            'items' => [
                ['value' => str_pad((string) max(count($benefits), 3), 2, '0', STR_PAD_LEFT), 'label' => 'Manfaat utama yang langsung terbaca'],
                ['value' => str_pad((string) max(count($features), 3), 2, '0', STR_PAD_LEFT), 'label' => 'Poin fitur yang mudah dijelaskan'],
                ['value' => '1', 'label' => 'CTA utama yang diarahkan jelas'],
            ],
        ];
    }

    protected function trustBadgesContent(array $theme): array
    {
        return [
            'eyebrow' => 'Trust Signal',
            'title' => 'Elemen yang membuat halaman ini lebih dipercaya',
            'items' => collect($theme['trust_elements'] ?? [])
                ->map(fn ($item) => ['title' => $item, 'copy' => 'Disusun untuk membantu pengunjung lebih cepat mengambil keputusan.'])
                ->values()
                ->all(),
        ];
    }

    protected function benefitsContent(array $input, array $theme): array
    {
        return [
            'eyebrow' => 'Manfaat Utama',
            'title' => 'Alasan utama kenapa target market akan tertarik',
            'items' => $this->splitLines((string) ($input['manfaat_utama'] ?? ''), 4),
        ];
    }

    protected function featuresContent(array $input, array $theme): array
    {
        return [
            'eyebrow' => 'Fitur / Highlight',
            'title' => 'Disusun supaya value produk atau layanan lebih konkret',
            'items' => $this->splitLines((string) ($input['fitur_utama'] ?? ''), 4),
        ];
    }

    protected function testimonialsContent(array $input, array $theme): array
    {
        $items = collect(preg_split('/\r\n|\r|\n/', (string) ($input['testimoni'] ?? '')) ?: [])
            ->map(fn ($line) => trim($line))
            ->filter()
            ->map(function (string $line) {
                [$name, $quote] = array_pad(explode('|', $line, 2), 2, null);

                return [
                    'name' => trim($name ?: 'Pelanggan'),
                    'quote' => trim($quote ?: $line),
                ];
            })
            ->take(3)
            ->values()
            ->all();

        if ($items === []) {
            $items = [
                ['name' => 'Pelanggan 1', 'quote' => 'Copy-nya lebih enak dibaca dan CTA-nya lebih jelas.'],
                ['name' => 'Pelanggan 2', 'quote' => 'Tampilan landing page terasa lebih meyakinkan dan siap dipromosikan.'],
            ];
        }

        return [
            'eyebrow' => 'Testimoni',
            'title' => 'Bukti sosial yang membantu calon pembeli lebih yakin',
            'items' => $items,
        ];
    }

    protected function pricingContent(array $input, array $theme): array
    {
        return [
            'eyebrow' => 'Penawaran',
            'title' => 'Tawaran utama yang paling mudah ditindaklanjuti',
            'price' => $input['harga'] ?: 'Hubungi Kami',
            'items' => array_merge(
                $this->splitLines((string) ($input['fitur_utama'] ?? ''), 3),
                $this->splitLines((string) ($input['keunggulan_kompetitor'] ?? ''), 2)
            ),
            'cta_text' => $input['cta_utama'] ?: 'Ambil Penawaran',
            'cta_link' => $input['cta_link'] ?: '#',
        ];
    }

    protected function galleryContent(array $input, array $theme): array
    {
        $main = $input['hero_image_url'] ?? null;
        $logo = $input['logo_url'] ?? null;

        $items = [
            ['title' => 'Hero Visual', 'caption' => 'Visual utama untuk memperkuat first impression.', 'image' => $main],
            ['title' => 'Brand Asset', 'caption' => 'Ruang untuk logo, mockup, atau materi visual brand.', 'image' => $logo],
            ['title' => 'Campaign Highlight', 'caption' => 'Section visual yang membantu produk terasa lebih hidup.', 'image' => $main],
        ];

        return [
            'eyebrow' => 'Gallery / Visual',
            'title' => 'Visual pendukung untuk memperkuat persepsi brand',
            'items' => $items,
        ];
    }

    protected function faqContent(array $input, array $theme): array
    {
        $items = collect(preg_split('/\r\n|\r|\n/', (string) ($input['faq_dasar'] ?? '')) ?: [])
            ->map(fn ($line) => trim($line))
            ->filter()
            ->map(function (string $line) {
                [$question, $answer] = array_pad(explode('|', $line, 2), 2, null);

                return [
                    'question' => trim($question ?: $line),
                    'answer' => trim($answer ?: 'Jawaban ini masih bisa Anda ubah di editor agar lebih sesuai dengan brand Anda.'),
                ];
            })
            ->take(4)
            ->values()
            ->all();

        if ($items === []) {
            $items = [
                ['question' => 'Apakah halaman ini bisa langsung dipakai?', 'answer' => 'Bisa. Setelah generate, Anda tinggal edit detail kecil lalu export HTML final.'],
                ['question' => 'Apakah CTA bisa diarahkan ke WhatsApp?', 'answer' => 'Bisa. Link CTA utama dapat diarahkan ke WhatsApp, checkout, marketplace, atau form.'],
                ['question' => 'Apakah tampilannya bisa diubah?', 'answer' => 'Bisa. Warna, font, urutan section, dan isi semua teks bisa diedit dari editor.'],
            ];
        }

        return [
            'eyebrow' => 'FAQ',
            'title' => 'Pertanyaan yang biasanya muncul sebelum orang klik CTA',
            'items' => $items,
        ];
    }

    protected function ctaContent(array $input, array $theme, array $hero): array
    {
        return [
            'eyebrow' => 'Siap Ambil Langkah Berikutnya?',
            'title' => 'Arahkan pengunjung ke tindakan paling penting sekarang.',
            'description' => $hero['subheadline'],
            'button' => $input['cta_utama'] ?: 'Hubungi Sekarang',
            'link' => $input['cta_link'] ?: '#',
        ];
    }

    protected function processContent(array $input, array $theme): array
    {
        return [
            'eyebrow' => 'Alur',
            'title' => 'Proses yang dibuat singkat dan mudah diikuti',
            'items' => [
                ['title' => 'Isi brief atau pilih layanan', 'copy' => 'Pengunjung memahami apa yang ditawarkan sejak awal.'],
                ['title' => 'Tentukan kebutuhan atau paket', 'copy' => 'Arahkan ke pilihan paling relevan untuk niche Anda.'],
                ['title' => 'Klik CTA dan lanjutkan komunikasi', 'copy' => 'WhatsApp, checkout, atau form langsung siap digunakan.'],
            ],
        ];
    }

    protected function comparisonContent(array $input, array $theme): array
    {
        $problem = $this->firstItem((string) ($input['masalah_utama'] ?? 'promosi yang belum rapi'));
        $advantage = $this->firstItem((string) ($input['keunggulan_kompetitor'] ?? 'lebih jelas, lebih fokus, dan lebih siap closing'));

        return [
            'eyebrow' => 'Perbandingan',
            'title' => 'Apa yang berubah ketika penawaran disusun lebih tepat',
            'items' => [
                ['title' => 'Sebelum', 'copy' => 'Calon pembeli masih bingung dengan '.$problem.'.'],
                ['title' => 'Sesudah', 'copy' => 'Penawaran terasa lebih kuat karena '.$advantage.'.'],
            ],
        ];
    }

    protected function packageContent(array $input, array $theme): array
    {
        return [
            'eyebrow' => 'Package Highlight',
            'title' => 'Susun penawaran agar lebih mudah dibandingkan dan dipilih',
            'items' => [
                ['name' => 'Starter', 'price' => $input['harga'] ?: 'Mulai dari Rp 199.000', 'copy' => 'Cocok untuk yang ingin mulai cepat.'],
                ['name' => 'Best Value', 'price' => $input['harga'] ?: 'Mulai dari Rp 299.000', 'copy' => 'Paket utama dengan kombinasi value paling seimbang.'],
                ['name' => 'Priority', 'price' => 'Custom', 'copy' => 'Untuk kebutuhan yang butuh fleksibilitas lebih tinggi.'],
            ],
        ];
    }

    protected function beforeAfterContent(array $input, array $theme): array
    {
        return [
            'eyebrow' => 'Before / After',
            'title' => 'Bantu audiens merasakan transformasi yang lebih nyata',
            'items' => [
                ['title' => 'Sebelum', 'copy' => $this->firstItem((string) ($input['masalah_utama'] ?? 'Masalah utama belum tersampaikan dengan rapi.'))],
                ['title' => 'Sesudah', 'copy' => $this->firstItem((string) ($input['manfaat_utama'] ?? 'Value proposition terasa lebih jelas dan lebih menarik.'))],
            ],
        ];
    }

    protected function itineraryContent(array $input, array $theme): array
    {
        return [
            'eyebrow' => 'Flow / Itinerary',
            'title' => 'Susunan pengalaman yang memudahkan orang membayangkan hasilnya',
            'items' => [
                ['day' => '01', 'title' => 'Mulai dari highlight utama', 'copy' => 'Tampilkan alasan kenapa penawaran ini menarik.'],
                ['day' => '02', 'title' => 'Masuk ke detail benefit', 'copy' => 'Bantu pengunjung memahami hasil yang akan dirasakan.'],
                ['day' => '03', 'title' => 'Akhiri dengan CTA yang jelas', 'copy' => 'Dorong tindakan saat minat sedang tinggi.'],
            ],
        ];
    }

    protected function imageContent(array $input, array $theme): array
    {
        $main = $input['hero_image_url'] ?? null;
        $logo = $input['logo_url'] ?? null;

        $default = data_get($this->registry(), 'image.default_content', []);
        $default['items'] = [
            ['id' => 'img_1', 'image_url' => $main, 'caption' => 'Hero Visual'],
            ['id' => 'img_2', 'image_url' => $logo, 'caption' => 'Brand Asset'],
            ['id' => 'img_3', 'image_url' => $main, 'caption' => 'Campaign Shot'],
        ];

        return $default;
    }

    protected function offerStackContent(array $input, array $theme): array
    {
        return [
            'eyebrow' => 'Bonus / Offer Stack',
            'title' => 'Value tambahan yang membuat penawaran lebih sulit diabaikan',
            'items' => [
                ['title' => 'Template siap pakai', 'copy' => 'Mempercepat implementasi sesudah deal atau pembelian.'],
                ['title' => 'Quickstart guide', 'copy' => 'Membantu user bergerak tanpa bingung di langkah awal.'],
                ['title' => 'Support dasar', 'copy' => 'Tambahan rasa aman sebelum mereka klik CTA.'],
            ],
        ];
    }

    protected function portfolioContent(array $input, array $theme): array
    {
        return [
            'eyebrow' => 'Portfolio / Showcase',
            'title' => 'Contoh hasil visual, dokumentasi, atau karya yang memperkuat kredibilitas',
            'items' => [
                ['title' => 'Showcase 01', 'caption' => 'Contoh visual utama yang relevan dengan niche.', 'image' => $input['hero_image_url'] ?? null],
                ['title' => 'Showcase 02', 'caption' => 'Bisa dipakai untuk portfolio, campaign, atau galeri hasil.', 'image' => $input['hero_image_url'] ?? null],
            ],
        ];
    }

    protected function guaranteeContent(array $input, array $theme): array
    {
        return [
            'eyebrow' => 'Guarantee / Trust',
            'title' => 'Tambahkan rasa aman sebelum pengunjung mengambil keputusan',
            'items' => [
                ['title' => 'Respons jelas', 'copy' => 'Calon pelanggan paham langkah berikutnya dengan cepat.'],
                ['title' => 'Offer lebih transparan', 'copy' => 'Benefit, CTA, dan ekspektasi terlihat lebih rapi.'],
                ['title' => 'Struktur siap closing', 'copy' => 'Halaman diarahkan untuk mendorong aksi utama.'],
            ],
        ];
    }

    protected function splitLines(string $value, int $limit = 5): array
    {
        return collect(preg_split('/\r\n|\r|\n|,/', $value) ?: [])
            ->map(fn ($item) => trim($item))
            ->filter()
            ->unique()
            ->take($limit)
            ->values()
            ->all() ?: ['Nilai utama produk disusun lebih jelas', 'Tampilan terasa lebih meyakinkan', 'CTA lebih mudah dipahami'];
    }

    protected function firstItem(string $value): string
    {
        return (string) collect(preg_split('/\r\n|\r|\n|,/', $value) ?: [])
            ->map(fn ($item) => trim($item))
            ->filter()
            ->first();
    }
}
