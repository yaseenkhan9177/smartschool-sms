#!/bin/bash
set -e

echo "==> Running Laravel startup tasks..."

# Wait briefly for DB to be ready (adjust if using a health check)
sleep 2

# Run database migrations
echo "==> Running migrations..."
php artisan migrate --force

# Create storage symlink (idempotent - safe to run every time)
echo "==> Linking storage..."
php artisan storage:link --force

# Cache config, routes, and views for production speed
echo "==> Caching config, routes, and views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Startup complete. Starting Apache..."
exec apache2-foreground
