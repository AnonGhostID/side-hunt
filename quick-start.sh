#!/bin/bash

echo "🚀 Quick Start - Laravel SideHunt Docker Deployment"
echo "=================================================="

# Check prerequisites
echo "🔍 Checking prerequisites..."

if ! command -v docker &> /dev/null; then
    echo "❌ Docker is not installed. Please install Docker first."
    echo "   Visit: https://docs.docker.com/get-docker/"
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose is not installed. Please install Docker Compose first."
    echo "   Visit: https://docs.docker.com/compose/install/"
    exit 1
fi

if ! docker info &> /dev/null; then
    echo "❌ Docker is not running. Please start Docker first."
    exit 1
fi

echo "✅ All prerequisites met!"

# Stop any existing containers
echo "🛑 Stopping any existing containers..."
docker-compose down -v 2>/dev/null || true

# Start the application
echo "🔨 Building and starting the application..."
echo "   This may take a few minutes on first run..."
docker-compose up -d --build

# Wait for services to be ready
echo "⏳ Waiting for services to initialize..."
echo "   This includes database migrations and cache setup..."

# Wait for MySQL to be healthy
echo "   - Waiting for MySQL..."
until docker-compose exec -T mysql mysqladmin ping -h localhost -u sidehunt_user -psidehunt_password --silent 2>/dev/null; do
    sleep 2
done

# Wait for Redis to be healthy
echo "   - Waiting for Redis..."
until docker-compose exec -T redis redis-cli ping 2>/dev/null | grep -q PONG; do
    sleep 2
done

# Wait for application to be ready
echo "   - Waiting for application..."
sleep 30

# Test the application
echo "🧪 Testing the application..."
if curl -f http://localhost > /dev/null 2>&1; then
    echo "✅ Application is ready!"
else
    echo "⚠️  Application might still be starting. Check logs with: docker-compose logs -f"
fi

echo ""
echo "🎉 Deployment Complete!"
echo "======================"
echo ""
echo "📋 Your application is now running:"
echo "   🌐 Web Application: http://localhost"
echo "   🗄️  MySQL Database: localhost:3306"
echo "   📦 Redis Cache: localhost:6379"
echo ""
echo "🔧 Useful Commands:"
echo "   📊 View logs: docker-compose logs -f"
echo "   🐚 Access shell: docker-compose exec app sh"
echo "   🗄️  Access database: docker-compose exec mysql mysql -u sidehunt_user -p sidehunt"
echo "   🛑 Stop services: docker-compose down"
echo "   🔄 Restart services: docker-compose restart"
echo ""
echo "📚 For more information, see README.Docker.md"
echo ""