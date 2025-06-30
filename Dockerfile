# Multi-stage Dockerfile for Laravel application

# Stage 1: Build frontend assets
FROM node:20-alpine AS frontend-builder

WORKDIR /app

# Copy package files
COPY package*.json ./

# Install dependencies (including dev dependencies for building)
RUN npm ci

# Copy source code
COPY . .

# Build frontend assets
RUN npm run build

# Stage 2: PHP application with Nginx
FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    oniguruma-dev \
    libzip-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    icu-dev \
    mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache

# Install Redis extension
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Copy application code
COPY . .

# Copy production environment file
COPY .env.docker .env

# Copy built frontend assets from frontend-builder stage
COPY --from=frontend-builder /app/public/build ./public/build

# Create necessary directories and set permissions
RUN mkdir -p \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/testing \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data \
        storage \
        bootstrap/cache \
        public \
    && chmod -R 775 \
        storage \
        bootstrap/cache

# Create nginx configuration
RUN mkdir -p /etc/nginx/http.d

# Nginx main configuration
RUN cat > /etc/nginx/nginx.conf << 'EOF'
user nginx;
worker_processes auto;
error_log /var/log/nginx/error.log warn;
pid /var/run/nginx.pid;

events {
    worker_connections 1024;
    use epoll;
    multi_accept on;
}

http {
    include /etc/nginx/mime.types;
    default_type application/octet-stream;

    log_format main '$remote_addr - $remote_user [$time_local] "$request" '
                    '$status $body_bytes_sent "$http_referer" '
                    '"$http_user_agent" "$http_x_forwarded_for"';

    access_log /var/log/nginx/access.log main;

    sendfile on;
    tcp_nopush on;
    tcp_nodelay on;
    keepalive_timeout 65;
    types_hash_max_size 2048;
    client_max_body_size 100M;

    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_types
        text/plain
        text/css
        text/xml
        text/javascript
        application/json
        application/javascript
        application/xml+rss
        application/atom+xml
        image/svg+xml;

    include /etc/nginx/http.d/*.conf;
}
EOF

# Nginx server configuration
RUN cat > /etc/nginx/http.d/default.conf << 'EOF'
server {
    listen 80;
    server_name localhost;
    root /var/www/html/public;
    index index.php index.html index.htm;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
        
        fastcgi_read_timeout 300;
        fastcgi_connect_timeout 300;
        fastcgi_send_timeout 300;
    }

    location ~ /\. {
        deny all;
    }

    location ~ /(?:\.env|\.git|composer\.json|composer\.lock|package\.json|package-lock\.json|yarn\.lock)$ {
        deny all;
    }

    location ~* \.(jpg|jpeg|png|gif|ico|css|js|pdf|txt|tar|woff|svg|ttf|eot|woff2)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    location = /favicon.ico {
        access_log off;
        log_not_found off;
    }

    location = /robots.txt {
        access_log off;
        log_not_found off;
    }

    error_page 404 /index.php;
    error_page 500 502 503 504 /index.php;
}
EOF

# PHP-FPM configuration
RUN cat > /usr/local/etc/php-fpm.d/www.conf << 'EOF'
[www]
user = www-data
group = www-data
listen = 127.0.0.1:9000
listen.owner = www-data
listen.group = www-data
pm = dynamic
pm.max_children = 20
pm.start_servers = 3
pm.min_spare_servers = 2
pm.max_spare_servers = 4
pm.max_requests = 500

catch_workers_output = yes
decorate_workers_output = no
clear_env = no

env[APP_NAME] = $APP_NAME
env[APP_ENV] = $APP_ENV
env[APP_KEY] = $APP_KEY
env[APP_DEBUG] = $APP_DEBUG
env[APP_URL] = $APP_URL
env[APP_TIMEZONE] = $APP_TIMEZONE
env[DB_CONNECTION] = $DB_CONNECTION
env[DB_HOST] = $DB_HOST
env[DB_PORT] = $DB_PORT
env[DB_DATABASE] = $DB_DATABASE
env[DB_USERNAME] = $DB_USERNAME
env[DB_PASSWORD] = $DB_PASSWORD
env[CACHE_DRIVER] = $CACHE_DRIVER
env[SESSION_DRIVER] = $SESSION_DRIVER
env[REDIS_HOST] = $REDIS_HOST
env[REDIS_PORT] = $REDIS_PORT
env[REDIS_PASSWORD] = $REDIS_PASSWORD
EOF

# PHP configuration
RUN cat > /usr/local/etc/php/conf.d/99-custom.ini << 'EOF'
memory_limit = 512M
max_execution_time = 300
max_input_time = 300

upload_max_filesize = 100M
post_max_size = 100M
max_file_uploads = 20

display_errors = Off
display_startup_errors = Off
log_errors = On
error_log = /var/log/php_errors.log

session.save_handler = redis
session.save_path = "tcp://redis:6379"
session.gc_maxlifetime = 7200

opcache.enable = 1
opcache.enable_cli = 1
opcache.memory_consumption = 128
opcache.interned_strings_buffer = 8
opcache.max_accelerated_files = 4000
opcache.revalidate_freq = 2
opcache.fast_shutdown = 1
opcache.validate_timestamps = 0

date.timezone = Asia/Jakarta
expose_php = Off
allow_url_fopen = On
allow_url_include = Off

realpath_cache_size = 4096K
realpath_cache_ttl = 600
EOF

# Supervisor configuration
RUN cat > /etc/supervisor/conf.d/supervisord.conf << 'EOF'
[supervisord]
nodaemon=true
user=root
logfile=/var/log/supervisor/supervisord.log
pidfile=/var/run/supervisord.pid

[program:php-fpm]
command=php-fpm -F
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0
autorestart=false
startretries=0

[program:nginx]
command=nginx -g 'daemon off;'
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0
autorestart=false
startretries=0
EOF

# Create startup script
RUN cat > /usr/local/bin/start.sh << 'EOF'
#!/bin/sh

set -e

echo "Starting Laravel application setup..."

echo "Waiting for MySQL to be ready..."
until mysqladmin ping -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" --silent; do
    echo "MySQL is unavailable - sleeping"
    sleep 2
done
echo "MySQL is ready!"

echo "Waiting for Redis to be ready..."
until redis-cli -h "$REDIS_HOST" -p "$REDIS_PORT" ping | grep -q PONG; do
    echo "Redis is unavailable - sleeping"
    sleep 2
done
echo "Redis is ready!"

cd /var/www/html

if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

echo "Creating storage link..."
php artisan storage:link || true

echo "Running database migrations..."
php artisan migrate --force

echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Clearing caches..."
php artisan cache:clear

echo "Setting file permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

mkdir -p /var/log/supervisor /var/log/nginx

echo "Laravel application setup completed!"

echo "Starting services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
EOF

# Create health check script
RUN cat > /usr/local/bin/healthcheck.sh << 'EOF'
#!/bin/sh

if ! pgrep nginx > /dev/null; then
    echo "Nginx is not running"
    exit 1
fi

if ! pgrep php-fpm > /dev/null; then
    echo "PHP-FPM is not running"
    exit 1
fi

if ! curl -f http://localhost/health > /dev/null 2>&1; then
    if ! curl -f http://localhost > /dev/null 2>&1; then
        echo "Application is not responding"
        exit 1
    fi
fi

echo "Application is healthy"
exit 0
EOF

# Make scripts executable
RUN chmod +x /usr/local/bin/start.sh /usr/local/bin/healthcheck.sh

# Create nginx user and group
RUN addgroup -g 82 -S nginx && adduser -u 82 -D -S -G nginx nginx

# Expose port 80
EXPOSE 80

# Start supervisor
CMD ["/usr/local/bin/start.sh"]