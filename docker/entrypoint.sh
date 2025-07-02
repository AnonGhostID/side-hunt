#!/bin/bash
set -e

cd /var/www/html

if [ ! -f .env ]; then
    cp .env.example .env
fi

# Wait for MySQL to be ready
until mysqladmin ping -h "$DB_HOST" --silent; do
    echo "Waiting for MySQL..."
    sleep 3
done

# Set permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Laravel setup
php artisan key:generate --force
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build assets
npm run build

# Start services
php-fpm -D
exec nginx -g 'daemon off;'
