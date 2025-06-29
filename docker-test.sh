#!/bin/bash

echo "🐳 Testing Docker setup for Laravel SideHunt application..."

# Check if docker-compose is available
if ! command -v docker-compose &> /dev/null; then
    echo "❌ docker-compose is not installed"
    exit 1
fi

# Check if Docker is running
if ! docker info &> /dev/null; then
    echo "❌ Docker is not running"
    exit 1
fi

echo "✅ Docker and docker-compose are available"

# Build and start the application
echo "🔨 Building and starting the application..."
docker-compose up -d --build

# Wait for services to be healthy
echo "⏳ Waiting for services to be healthy..."
sleep 30

# Check if services are running
echo "🔍 Checking service status..."

APP_STATUS=$(docker-compose ps app | grep "Up" | wc -l)
MYSQL_STATUS=$(docker-compose ps mysql | grep "Up" | wc -l)
REDIS_STATUS=$(docker-compose ps redis | grep "Up" | wc -l)

if [ $APP_STATUS -eq 1 ]; then
    echo "✅ Application container is running"
else
    echo "❌ Application container is not running"
    docker-compose logs app
    exit 1
fi

if [ $MYSQL_STATUS -eq 1 ]; then
    echo "✅ MySQL container is running"
else
    echo "❌ MySQL container is not running"
    docker-compose logs mysql
    exit 1
fi

if [ $REDIS_STATUS -eq 1 ]; then
    echo "✅ Redis container is running"
else
    echo "❌ Redis container is not running"
    docker-compose logs redis
    exit 1
fi

# Test HTTP response
echo "🌐 Testing HTTP response..."
if curl -f http://localhost > /dev/null 2>&1; then
    echo "✅ Application is responding on http://localhost"
else
    echo "❌ Application is not responding on http://localhost"
    echo "📋 Application logs:"
    docker-compose logs app --tail=20
    exit 1
fi

# Test database connection
echo "🗄️ Testing database connection..."
if docker-compose exec -T mysql mysqladmin ping -h localhost -u sidehunt_user -psidehunt_password --silent; then
    echo "✅ Database connection is working"
else
    echo "❌ Database connection failed"
    exit 1
fi

# Test Redis connection
echo "📦 Testing Redis connection..."
if docker-compose exec -T redis redis-cli ping | grep -q PONG; then
    echo "✅ Redis connection is working"
else
    echo "❌ Redis connection failed"
    exit 1
fi

echo ""
echo "🎉 All tests passed! Your Laravel application is successfully running in Docker."
echo ""
echo "📋 Service URLs:"
echo "   Application: http://localhost"
echo "   MySQL: localhost:3306"
echo "   Redis: localhost:6379"
echo ""
echo "🔧 Useful commands:"
echo "   View logs: docker-compose logs -f"
echo "   Access shell: docker-compose exec app sh"
echo "   Stop services: docker-compose down"
echo ""