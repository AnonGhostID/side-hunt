#!/usr/bin/env pwsh

Write-Host "🐳 Testing Docker setup for Laravel SideHunt application..." -ForegroundColor Cyan

# Check if docker-compose is available
if (-not (Get-Command docker-compose -ErrorAction SilentlyContinue)) {
    Write-Host "❌ docker-compose is not installed" -ForegroundColor Red
    exit 1
}

# Check if Docker is running
try {
    docker info | Out-Null
    Write-Host "✅ Docker and docker-compose are available" -ForegroundColor Green
} catch {
    Write-Host "❌ Docker is not running" -ForegroundColor Red
    exit 1
}

# Build and start the application
Write-Host "🔨 Building and starting the application..." -ForegroundColor Yellow
docker-compose up -d --build

# Wait for services to be healthy
Write-Host "⏳ Waiting for services to be healthy..." -ForegroundColor Yellow
Start-Sleep -Seconds 30

# Check if services are running
Write-Host "🔍 Checking service status..." -ForegroundColor Yellow

$appStatus = docker-compose ps app | Select-String "Up"
$mysqlStatus = docker-compose ps mysql | Select-String "Up"
$redisStatus = docker-compose ps redis | Select-String "Up"

if ($appStatus) {
    Write-Host "✅ Application container is running" -ForegroundColor Green
} else {
    Write-Host "❌ Application container is not running" -ForegroundColor Red
    docker-compose logs app
    exit 1
}

if ($mysqlStatus) {
    Write-Host "✅ MySQL container is running" -ForegroundColor Green
} else {
    Write-Host "❌ MySQL container is not running" -ForegroundColor Red
    docker-compose logs mysql
    exit 1
}

if ($redisStatus) {
    Write-Host "✅ Redis container is running" -ForegroundColor Green
} else {
    Write-Host "❌ Redis container is not running" -ForegroundColor Red
    docker-compose logs redis
    exit 1
}

# Test HTTP response
Write-Host "🌐 Testing HTTP response..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "http://localhost" -UseBasicParsing -TimeoutSec 10
    if ($response.StatusCode -eq 200) {
        Write-Host "✅ Application is responding on http://localhost" -ForegroundColor Green
    } else {
        throw "HTTP status: $($response.StatusCode)"
    }
} catch {
    Write-Host "❌ Application is not responding on http://localhost" -ForegroundColor Red
    Write-Host "📋 Application logs:" -ForegroundColor Yellow
    docker-compose logs app --tail=20
    exit 1
}

# Test database connection
Write-Host "🗄️ Testing database connection..." -ForegroundColor Yellow
$dbTest = docker-compose exec -T mysql mysqladmin ping -h localhost -u sidehunt_user -psidehunt_password --silent
if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Database connection is working" -ForegroundColor Green
} else {
    Write-Host "❌ Database connection failed" -ForegroundColor Red
    exit 1
}

# Test Redis connection
Write-Host "📦 Testing Redis connection..." -ForegroundColor Yellow
$redisTest = docker-compose exec -T redis redis-cli ping
if ($redisTest -match "PONG") {
    Write-Host "✅ Redis connection is working" -ForegroundColor Green
} else {
    Write-Host "❌ Redis connection failed" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "🎉 All tests passed! Your Laravel application is successfully running in Docker." -ForegroundColor Green
Write-Host ""
Write-Host "📋 Service URLs:" -ForegroundColor Cyan
Write-Host "   Application: http://localhost" -ForegroundColor White
Write-Host "   MySQL: localhost:3306" -ForegroundColor White
Write-Host "   Redis: localhost:6379" -ForegroundColor White
Write-Host ""
Write-Host "🔧 Useful commands:" -ForegroundColor Cyan
Write-Host "   View logs: docker-compose logs -f" -ForegroundColor White
Write-Host "   Access shell: docker-compose exec app sh" -ForegroundColor White
Write-Host "   Stop services: docker-compose down" -ForegroundColor White
Write-Host ""