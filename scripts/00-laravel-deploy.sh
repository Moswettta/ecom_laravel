#!/usr/bin/env bash
set -e

echo "==> Composer install"
composer install --no-dev --working-dir=/var/www/html --optimize-autoloader --no-interaction

echo "==> Config cache"
php artisan config:cache

echo "==> Route cache"
php artisan route:cache || true

echo "==> View cache"
php artisan view:cache || true

echo "==> Storage link"
php artisan storage:link || true

echo "==> Migrate"
php artisan migrate --force

echo "==> Seed (safe if already seeded)"
php artisan db:seed --force || true

echo "==> Deploy script done"
