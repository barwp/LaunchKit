# Deploy Production

Folder ini berisi baseline deploy full Laravel `LaunchKit Adaptive` untuk server production yang menjalankan:

- PHP 8.3+
- MySQL 8+
- Nginx
- PHP-FPM
- Supervisor

## Struktur

- `deploy/nginx/launchkit.conf`
- `deploy/supervisor/launchkit-worker.conf`
- `scripts/production-deploy.sh`

## Alur umum

1. Clone repo ke server
2. Copy `.env.production.example` menjadi `.env`
3. Isi nilai `APP_URL`, `DB_*`, dan setting production lain
4. Jalankan:

```bash
bash scripts/production-deploy.sh
```

## Hal penting

- web root Nginx harus diarahkan ke folder `public`
- queue worker perlu aktif untuk queue `database`
- pastikan `storage` dan `bootstrap/cache` writable
- jalankan HTTPS
- untuk upload file production, `FILESYSTEM_DISK=public`

## Setelah deploy

Jalankan pengecekan:

```bash
php artisan about
php artisan migrate:status
php artisan queue:work --once
```
