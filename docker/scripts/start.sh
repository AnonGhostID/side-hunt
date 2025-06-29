#!/bin/sh

# Exit on any error
set -e

echo "Starting Laravel application setup..."

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
until mysqladmin ping -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" --silent; do
    echo "MySQL is unavailable - sleeping"
    sleep 2
done
echo "MySQL is ready!"

# Wait for Redis to be ready
echo "Waiting for Redis to be ready..."
until redis-cli -h "$REDIS_HOST" -p "$REDIS_PORT" ping | grep -q PONG; do
    echo "Redis is unavailable - sleeping"
    sleep 2
done
echo "Redis is ready!"

# Navigate to application directory
cd /var/www/html

# Generate application key if not exists
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Create storage link
echo "Creating storage link..."
php artisan storage:link || true

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# Cache configuration for better performance
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear any existing caches
echo "Clearing caches..."
php artisan cache:clear

# Set proper permissions
echo "Setting file permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create log directories
mkdir -p /var/log/supervisor /var/log/nginx

echo "Laravel application setup completed!"

# Start supervisor to manage nginx and php-fpm
echo "Starting services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf