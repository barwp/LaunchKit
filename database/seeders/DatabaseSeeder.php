<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Order;
use App\Models\User;
use App\Services\AffiliateService;
use App\Services\LandingPageGenerator;
use App\Services\ReferralService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $referralService = app(ReferralService::class);
        $affiliateService = app(AffiliateService::class);

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@launchkit.test'],
            ['name' => 'LaunchKit Admin', 'is_admin' => true, 'password' => Hash::make('Admin12345!')]
        );
        $referralService->ensureReferralCode($admin);
        $affiliateService->ensureWallet($admin);

        $user = User::query()->updateOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'is_admin' => false, 'password' => Hash::make('password')]
        );
        $referralService->ensureReferralCode($user);
        $affiliateService->ensureWallet($user);

        $pendingUser = User::query()->updateOrCreate(
            ['email' => 'pending@launchkit.test'],
            [
                'name' => 'Pending Payment User',
                'is_admin' => false,
                'password' => Hash::make('password'),
                'account_status' => 'pending_payment',
                'referred_by' => $user->id,
            ]
        );
        $referralService->ensureReferralCode($pendingUser);
        $affiliateService->ensureWallet($pendingUser);

        Order::query()->updateOrCreate(
            ['user_id' => $pendingUser->id, 'package_name' => 'LaunchKit Starter'],
            [
                'price' => 99000,
                'discount' => 5000,
                'final_price' => 94000,
                'referral_code_used' => $user->referral_code,
                'status' => 'pending',
            ]
        );

        $generator = app(LandingPageGenerator::class);

        foreach ($this->sampleProjects() as $projectInput) {
            $generated = $generator->generate($projectInput);

            Project::query()->updateOrCreate(
                ['user_id' => $user->id, 'name' => $projectInput['nama_project']],
                [
                    'niche' => $projectInput['niche'],
                    'business_type' => $projectInput['business_type'],
                    'raw_input' => $projectInput,
                    'generated_data' => $generated,
                    'edited_data' => $generated,
                ]
            );
        }
    }

    protected function sampleProjects(): array
    {
        return [
            [
                'nama_project' => 'Crypto Class Launch',
                'nama_brand_bisnis' => 'Akademi Crypto',
                'niche' => 'edukasi-course',
                'business_type' => 'digital product',
                'nama_produk_layanan' => 'Kelas Akademi Crypto',
                'deskripsi_singkat' => 'Program belajar crypto untuk pemula yang ingin paham dasar, analisa, dan praktik dengan langkah yang rapi.',
                'target_market' => 'pelajar dan mahasiswa',
                'harga' => 'Rp 199.000',
                'cta_utama' => 'Gabung Sekarang',
                'cta_link' => 'https://wa.me/6281234567890',
                'masalah_utama' => 'bingung mulai belajar crypto dari mana',
                'manfaat_utama' => "Paham dasar crypto dengan benar\nBelajar lebih terstruktur\nPunya komunitas supportif\nBisa mulai dengan modal kecil",
                'fitur_utama' => "Video materi terstruktur\nTemplate checklist siap pakai\nUpdate market mingguan\nGrup diskusi eksklusif",
                'keunggulan_kompetitor' => "Bahasa mudah dipahami\nFokus untuk pemula\nBisa langsung praktik",
                'testimoni' => "Raka | Materinya jelas dan tidak bikin bingung untuk pemula.\nNisa | Value yang didapat terasa lebih rapi dan meyakinkan.",
                'faq_dasar' => "Apakah cocok untuk pemula? | Ya, materi disusun dari dasar.\nApakah ada grup diskusi? | Ada, grup eksklusif untuk member.",
                'visual_preference' => 'modern',
                'tone_copy' => 'confident',
            ],
            [
                'nama_project' => 'Skincare Glow Daily',
                'nama_brand_bisnis' => 'Luma Skin',
                'niche' => 'skincare',
                'business_type' => 'produk',
                'nama_produk_layanan' => 'Glow Serum Daily',
                'deskripsi_singkat' => 'Serum harian dengan feel ringan untuk membantu kulit tampak lebih fresh dan terawat.',
                'target_market' => 'wanita aktif usia 20-35',
                'harga' => 'Rp 149.000',
                'cta_utama' => 'Order Sekarang',
                'cta_link' => 'https://wa.me/6281234567890',
                'masalah_utama' => 'kulit kusam dan makeup kurang menempel',
                'manfaat_utama' => "Kulit tampak lebih fresh\nTekstur terasa ringan\nLebih nyaman dipakai harian\nBantu tampilan lebih glowing",
                'fitur_utama' => "Niacinamide\nHydrating texture\nCepat meresap\nAman untuk rutinitas pagi",
                'keunggulan_kompetitor' => "Formula ringan\nPackaging premium\nCocok untuk daily use",
                'testimoni' => "Alya | Kulitku kelihatan lebih fresh dalam beberapa hari.\nTari | Teksturnya ringan dan enak dipakai tiap pagi.",
                'faq_dasar' => "Apakah bisa dipakai pagi hari? | Bisa, justru cocok untuk rutinitas harian.\nApakah terasa lengket? | Tidak, teksturnya ringan dan cepat meresap.",
                'visual_preference' => 'feminine',
                'tone_copy' => 'soft',
            ],
            [
                'nama_project' => 'Studio Website UMKM',
                'nama_brand_bisnis' => 'Northlane Studio',
                'niche' => 'agency-studio-design',
                'business_type' => 'jasa',
                'nama_produk_layanan' => 'Paket Landing Page UMKM',
                'deskripsi_singkat' => 'Studio desain yang membantu UMKM tampil lebih tajam dan siap closing lewat landing page adaptif.',
                'target_market' => 'UMKM dan bisnis online',
                'harga' => 'Mulai Rp 1.500.000',
                'cta_utama' => 'Konsultasi Sekarang',
                'cta_link' => 'https://wa.me/6281234567890',
                'masalah_utama' => 'promosi tidak konsisten dan landing page belum meyakinkan',
                'manfaat_utama' => "Tampilan brand lebih profesional\nCTA lebih jelas\nSiap dipromosikan ke ads\nStruktur copy lebih fokus",
                'fitur_utama' => "Desain custom\nCopywriting adaptif\nEditor ringan\nExport HTML siap deploy",
                'keunggulan_kompetitor' => "Lebih cepat launch\nFokus ke conversion\nDesain terasa premium",
                'testimoni' => "Rina | Presentasi brand kami jadi lebih profesional.\nDimas | Landing page-nya enak dipakai untuk kampanye ads.",
                'faq_dasar' => "Apakah bisa request revisi? | Bisa, semua hasil tetap editable.\nApakah siap untuk iklan? | Ya, struktur halaman dibuat conversion-focused.",
                'visual_preference' => 'premium',
                'tone_copy' => 'professional',
            ],
            [
                'nama_project' => 'Open Trip Raja Ampat',
                'nama_brand_bisnis' => 'Langit Timur Trip',
                'niche' => 'travel',
                'business_type' => 'jasa',
                'nama_produk_layanan' => 'Open Trip Raja Ampat 4D3N',
                'deskripsi_singkat' => 'Paket trip seru untuk kamu yang ingin liburan lebih praktis dengan itinerary yang sudah rapi.',
                'target_market' => 'traveler muda dan pekerja remote',
                'harga' => 'Mulai Rp 3.490.000',
                'cta_utama' => 'Booking Sekarang',
                'cta_link' => 'https://wa.me/6281234567890',
                'masalah_utama' => 'ingin liburan tapi tidak mau ribet urus itinerary',
                'manfaat_utama' => "Liburan lebih praktis\nDestinasi pilihan lebih cantik\nJadwal lebih rapi\nBooking lebih gampang",
                'fitur_utama' => "Itinerary jelas\nHotel dan transport siap\nSpot foto terbaik\nTim leader berpengalaman",
                'keunggulan_kompetitor' => "Trip lebih terarah\nSupport admin cepat\nVisual destinasi lebih premium",
                'testimoni' => "Fani | Trip-nya rapi dan suasananya seru banget.\nIqbal | Booking-nya gampang dan itinerary-nya jelas.",
                'faq_dasar' => "Apakah cocok untuk solo traveler? | Sangat cocok.\nApakah itinerary sudah termasuk transport lokal? | Ya, sudah disiapkan.",
                'visual_preference' => 'fun',
                'tone_copy' => 'exciting',
            ],
        ];
    }
}
