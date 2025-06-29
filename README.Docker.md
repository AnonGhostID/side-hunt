# Docker Deployment Guide

This Laravel application has been configured for Docker deployment with a complete production-ready setup.

## Quick Start

1. **Clone the repository and navigate to the project directory**
2. **Start the application:**
   ```bash
   docker-compose up -d
   ```
3. **Access the application:**
   - Application: http://localhost
   - MySQL: localhost:3306
   - Redis: localhost:6379

That's it! The application will be fully configured and ready to use.

## What Happens Automatically

When you run `docker-compose up`, the following happens automatically:

1. **Frontend Build**: Node.js builds all frontend assets using Vite
2. **PHP Dependencies**: Composer installs all PHP dependencies
3. **Database Setup**: MySQL container starts with proper configuration
4. **Redis Setup**: Redis container starts for caching and sessions
5. **Laravel Configuration**:
   - Application key generation
   - Database migrations
   - Configuration caching
   - Route caching
   - View caching
   - Storage link creation
6. **Web Server**: Nginx serves the application with optimized PHP-FPM

## Services

### Application (app)
- **Image**: Custom built from Dockerfile
- **Port**: 80
- **Includes**: PHP 8.2, Nginx, Laravel application
- **Features**: 
  - Automatic Laravel setup
  - Optimized PHP-FPM configuration
  - Production-ready Nginx configuration
  - Redis integration for sessions and caching

### Database (mysql)
- **Image**: MySQL 8.0
- **Port**: 3306
- **Database**: sidehunt
- **User**: sidehunt_user
- **Password**: sidehunt_password
- **Root Password**: root_password

### Cache (redis)
- **Image**: Redis 7 Alpine
- **Port**: 6379
- **Usage**: Sessions, caching

## Environment Variables

The application uses production-optimized environment variables defined in `docker-compose.yml`. Key settings:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `CACHE_DRIVER=redis`
- `SESSION_DRIVER=redis`
- Database connection to MySQL container
- Redis connection for caching

## Persistent Data

The following data is persisted across container restarts:

- **MySQL Data**: Stored in `mysql_data` volume
- **Redis Data**: Stored in `redis_data` volume
- **Application Storage**: Stored in `storage_data` volume
- **Logs**: Mapped to `./storage/logs` on host

## Development vs Production

This Docker setup is configured for production deployment. For development:

1. **Change environment variables** in `docker-compose.yml`:
   ```yaml
   - APP_ENV=local
   - APP_DEBUG=true
   ```

2. **Add volume mounts** for live code editing:
   ```yaml
   volumes:
     - .:/var/www/html
   ```

## Customization

### Environment Variables
Modify the environment variables in `docker-compose.yml` or create a `.env` file for docker-compose.

### Database Configuration
Update MySQL settings in `docker/mysql/my.cnf`.

### PHP Configuration
Modify PHP settings in `docker/php/php.ini`.

### Nginx Configuration
Update web server settings in `docker/nginx/default.conf`.

## Deployment Platforms

This setup is compatible with:
- **Dokploy**
- **Docker Swarm**
- **Kubernetes** (with additional manifests)
- **AWS ECS**
- **Google Cloud Run**
- **Azure Container Instances**

## Troubleshooting

### Check container logs:
```bash
docker-compose logs app
docker-compose logs mysql
docker-compose logs redis
```

### Access application container:
```bash
docker-compose exec app sh
```

### Reset everything:
```bash
docker-compose down -v
docker-compose up -d
```

### Database issues:
```bash
# Check MySQL connection
docker-compose exec mysql mysql -u sidehunt_user -p sidehunt

# Run migrations manually
docker-compose exec app php artisan migrate
```

## Security Notes

For production deployment:
1. Change default passwords in `docker-compose.yml`
2. Use Docker secrets for sensitive data
3. Configure proper firewall rules
4. Use HTTPS with SSL certificates
5. Regular security updates

## Performance Optimization

The setup includes:
- OPcache enabled for PHP
- Redis for sessions and caching
- Nginx with gzip compression
- Optimized MySQL configuration
- Production asset compilation