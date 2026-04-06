# LaunchKit Adaptive

LaunchKit Adaptive adalah aplikasi web Laravel untuk membuat landing page otomatis dari brief bisnis, lalu menyesuaikan hero, tone, section order, CTA, warna, dan gaya visual berdasarkan niche yang dipilih user. Hasil generate bisa diedit bebas di visual editor dan diexport menjadi HTML statis.

## Stack

- Laravel 13
- Laravel Breeze (Blade auth)
- Blade
- Tailwind CSS 3
- Alpine.js
- SQLite untuk development
- MySQL siap dipakai untuk staging/production

## Fitur Utama

- Auth register, login, dashboard user
- Project landing page per user
- Form multi-step untuk brief bisnis, value marketing, dan visual
- Adaptive niche template engine untuk 20 niche
- Generator modular:
  - `App\Services\NicheThemeResolver`
  - `App\Services\HeroGenerator`
  - `App\Services\SectionComposer`
  - `App\Services\LandingPageGenerator`
- Hero generator dengan headline pendek maksimal 10 kata
- Visual editor untuk edit hero, section, warna, urutan, hide/show, dan font
- Duplicate project
- Export HTML statis tanpa UI editor
- Seeder contoh project untuk beberapa niche

## Struktur Penting

```text
app/Http/Controllers/ProjectController.php
app/Models/Project.php
app/Services/NicheThemeResolver.php
app/Services/HeroGenerator.php
app/Services/SectionComposer.php
app/Services/LandingPageGenerator.php
config/landing_themes.php
database/migrations/2026_04_05_000003_create_projects_table.php
database/seeders/DatabaseSeeder.php
resources/views/dashboard.blade.php
resources/views/projects/create.blade.php
resources/views/projects/edit.blade.php
resources/views/projects/export.blade.php
resources/views/projects/partials/
resources/views/sections/
routes/web.php
```

## Route

- `GET /dashboard`
- `GET /projects/create`
- `POST /projects/store`
- `GET /projects/{project}/edit`
- `PUT /projects/{project}/update`
- `GET /projects/{project}/export`
- `POST /projects/{project}/duplicate`

## Adaptive Engine

Setiap niche dapat diatur dari `config/landing_themes.php` tanpa mengubah logic utama generator.

Setiap config niche mendefinisikan:

- label niche
- visual mood
- color palette
- font preset
- button style
- hero style
- section order
- recommended sections
- copy tone
- CTA style
- trust elements
- card radius
- spacing density
- background pattern
- layout style
- icon style

## Hero Generator

Aturan utama generator hero:

- headline maksimal 10 kata
- fokus pada satu ide utama
- detail dipindah ke subheadline
- mobile-friendly
- fallback otomatis jika input user terlalu panjang

Pola headline yang tersedia:

- problem-based
- result-based
- transformation-based
- offer-based
- trust-based

## Setup

Kebutuhan:

- PHP 8.3+
- Composer
- Node.js 20+
- npm

Instalasi SQLite:

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan storage:link
touch database/database.sqlite
php artisan migrate --seed
npm install
npm run dev
php artisan serve
```

Buka aplikasi:

```text
http://127.0.0.1:8000
```

## Setup MySQL

Project ini sudah siap dipakai di MySQL tanpa ubah logic aplikasi, karena schema dibangun penuh lewat migration Laravel.

Langkah cepat:

```bash
cp .env.mysql.example .env
composer install
php artisan key:generate
php artisan storage:link
php artisan migrate --seed
npm install
npm run dev
php artisan serve
```

Jika Anda sudah punya `.env` dan hanya ingin pindah koneksi database, ubah nilai berikut:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=launchkit_adaptive
DB_USERNAME=root
DB_PASSWORD=
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
```

Lalu jalankan:

```bash
php artisan migrate:fresh --seed
```

Catatan MySQL:

- Disarankan memakai MySQL 8+.
- Kolom `json` yang dipakai project ini kompatibel dengan MySQL modern.
- Foreign key di migration sudah aman untuk MySQL.
- Tidak ada migration yang saat ini perlu diubah khusus untuk MySQL.

## Akun Demo Seeder

Seeder membuat 1 akun demo:

- Email: `test@example.com`
- Password: `password`

Seeder juga membuat beberapa contoh project adaptive agar dashboard langsung terisi.

## Export HTML

Fitur export menghasilkan file HTML statis yang:

- tanpa script editor
- tetap membawa styling utama
- siap diupload ke shared hosting
- bisa diedit manual bila diperlukan

## Development Notes

- Upload logo dan hero image disimpan ke `storage/app/public`
- Jika asset upload tidak muncul, pastikan `php artisan storage:link` sudah dijalankan
- Generator masih rule-based, jadi sangat mudah dikembangkan ke AI-assisted generation nanti

## Menambah Niche Baru

Untuk menambah niche baru, cukup:

1. Tambahkan config niche di `config/landing_themes.php`
2. Pastikan `section_order` dan `recommended_sections` memakai partial section yang tersedia
3. Jika perlu section baru, tambahkan partial Blade di `resources/views/sections`

Logic utama generator tidak perlu diubah selama struktur config tetap konsisten.
