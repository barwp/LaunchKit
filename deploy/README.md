# Deploy Production

Folder ini berisi baseline deploy full Laravel `LaunchKit Adaptive` untuk server production yang menjalankan:

- PHP 8.3+
- MySQL 8+
- Nginx
- Apache (opsional)
- PHP-FPM
- Supervisor

## Struktur

- `deploy/nginx/launchkit.conf`
- `deploy/apache/launchkit.conf`
- `deploy/supervisor/launchkit-worker.conf`
- `scripts/production-deploy.sh`
- `scripts/post-deploy-check.sh`

## Alur umum

1. Clone repo ke server
2. Copy `.env.production.example` menjadi `.env`
3. Isi nilai `APP_URL`, `DB_*`, dan setting production lain
4. Jalankan:

```bash
bash scripts/production-deploy.sh
```

5. Cek public route supaya tidak 404:

```bash
bash scripts/post-deploy-check.sh https://your-domain.com
```

## Hal penting

- web root Nginx harus diarahkan ke folder `public`
- untuk Apache, `DocumentRoot` juga harus diarahkan ke folder `public`
- queue worker perlu aktif untuk queue `database`
- pastikan `storage` dan `bootstrap/cache` writable
- jalankan HTTPS
- untuk upload file production, `FILESYSTEM_DISK=public`
- route health tersedia di `/health`

## Setelah deploy

Jalankan pengecekan:

```bash
php artisan about
php artisan migrate:status
php artisan queue:work --once
bash scripts/post-deploy-check.sh https://your-domain.com
```

## GitHub Pages

GitHub Pages hanya bisa dipakai untuk halaman statis seperti `docs/index.html`.
Full aplikasi Laravel builder tidak bisa berjalan penuh di GitHub Pages karena butuh PHP + MySQL.

Jika URL Pages masih `404`, cek repository:

1. `Settings > Pages`
2. `Build and deployment`
3. Pastikan source memakai `GitHub Actions`
4. Simpan perubahan lalu push lagi bila perlu
