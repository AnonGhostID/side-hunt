#!/usr/bin/env pwsh

Write-Host "🚀 Quick Start - Laravel SideHunt Docker Deployment" -ForegroundColor Cyan
Write-Host "==================================================" -ForegroundColor Cyan

# Check prerequisites
Write-Host "🔍 Checking prerequisites..." -ForegroundColor Yellow

if (-not (Get-Command docker -ErrorAction SilentlyContinue)) {
    Write-Host "❌ Docker is not installed. Please install Docker first." -ForegroundColor Red
    Write-Host "   Visit: https://docs.docker.com/get-docker/" -ForegroundColor White
    exit 1
}

if (-not (Get-Command docker-compose -ErrorAction SilentlyContinue)) {
    Write-Host "❌ Docker Compose is not installed. Please install Docker Compose first." -ForegroundColor Red
    Write-Host "   Visit: https://docs.docker.com/compose/install/" -ForegroundColor White
    exit 1
}

try {
    docker info | Out-Null
} catch {
    Write-Host "❌ Docker is not running. Please start Docker first." -ForegroundColor Red
    exit 1
}

Write-Host "✅ All prerequisites met!" -ForegroundColor Green

# Stop any existing containers
Write-Host "🛑 Stopping any existing containers..." -ForegroundColor Yellow
docker-compose down -v 2>$null

# Start the application
Write-Host "🔨 Building and starting the application..." -ForegroundColor Yellow
Write-Host "   This may take a few minutes on first run..." -ForegroundColor Gray
Write-Host "   Building Docker image with embedded configuration..." -ForegroundColor Gray
docker-compose up -d --build

# Wait for services to be ready
Write-Host "⏳ Waiting for services to initialize..." -ForegroundColor Yellow
Write-Host "   This includes database migrations and cache setup..." -ForegroundColor Gray

# Wait for MySQL to be healthy
Write-Host "   - Waiting for MySQL..." -ForegroundColor Gray
do {
    Start-Sleep -Seconds 2
    $mysqlReady = docker-compose exec -T mysql mysqladmin ping -h localhost -u sidehunt_user -psidehunt_password --silent 2>$null
} while ($LASTEXITCODE -ne 0)

# Wait for Redis to be healthy
Write-Host "   - Waiting for Redis..." -ForegroundColor Gray
do {
    Start-Sleep -Seconds 2
    $redisReady = docker-compose exec -T redis redis-cli ping 2>$null
} while (-not ($redisReady -match "PONG"))

# Wait for application to be ready
Write-Host "   - Waiting for application..." -ForegroundColor Gray
Start-Sleep -Seconds 30

# Test the application
Write-Host "🧪 Testing the application..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "http://localhost" -UseBasicParsing -TimeoutSec 10
    if ($response.StatusCode -eq 200) {
        Write-Host "✅ Application is ready!" -ForegroundColor Green
    } else {
        Write-Host "⚠️  Application might still be starting. Check logs with: docker-compose logs -f" -ForegroundColor Yellow
    }
} catch {
    Write-Host "⚠️  Application might still be starting. Check logs with: docker-compose logs -f" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "🎉 Deployment Complete!" -ForegroundColor Green
Write-Host "======================" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Your application is now running:" -ForegroundColor Cyan
Write-Host "   🌐 Web Application: http://localhost" -ForegroundColor White
Write-Host "   🗄️  MySQL Database: localhost:3306" -ForegroundColor White
Write-Host "   📦 Redis Cache: localhost:6379" -ForegroundColor White
Write-Host ""
Write-Host "🔧 Useful Commands:" -ForegroundColor Cyan
Write-Host "   📊 View logs: docker-compose logs -f" -ForegroundColor White
Write-Host "   🐚 Access shell: docker-compose exec app sh" -ForegroundColor White
Write-Host "   🗄️  Access database: docker-compose exec mysql mysql -u sidehunt_user -p sidehunt" -ForegroundColor White
Write-Host "   🛑 Stop services: docker-compose down" -ForegroundColor White
Write-Host "   🔄 Restart services: docker-compose restart" -ForegroundColor White
Write-Host ""
Write-Host "📚 For more information, see README.Docker.md" -ForegroundColor Cyan
Write-Host ""