#!/bin/sh

# Health check script for Laravel application

# Check if Nginx is running
if ! pgrep nginx > /dev/null; then
    echo "Nginx is not running"
    exit 1
fi

# Check if PHP-FPM is running
if ! pgrep php-fpm > /dev/null; then
    echo "PHP-FPM is not running"
    exit 1
fi

# Check if the application responds
if ! curl -f http://localhost/health > /dev/null 2>&1; then
    # If health endpoint fails, check the main page
    if ! curl -f http://localhost > /dev/null 2>&1; then
        echo "Application is not responding"
        exit 1
    fi
fi

echo "Application is healthy"
exit 0