#!/usr/bin/env bash

set -euo pipefail

echo "==> Installing composer dependencies"
composer install --no-dev --optimize-autoloader --no-interaction

echo "==> Installing node dependencies"
npm install

echo "==> Building frontend assets"
npm run build

echo "==> Preparing Laravel"
php artisan key:generate --force || true
php artisan storage:link || true
php artisan migrate --force

echo "==> Caching configuration"
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Done"
echo "Remember to restart php-fpm, queue worker, and web server if needed."
