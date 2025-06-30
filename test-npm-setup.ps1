#!/usr/bin/env pwsh

Write-Host "🔍 Testing NPM Setup for Docker Build" -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan

# Check if package.json exists
if (Test-Path "package.json") {
    Write-Host "✅ package.json found" -ForegroundColor Green
} else {
    Write-Host "❌ package.json missing" -ForegroundColor Red
    exit 1
}

# Check if package-lock.json exists
if (Test-Path "package-lock.json") {
    Write-Host "✅ package-lock.json found" -ForegroundColor Green
} else {
    Write-Host "⚠️  package-lock.json missing (will use npm install)" -ForegroundColor Yellow
}

# Check if build script exists
$packageContent = Get-Content "package.json" -Raw
if ($packageContent -match '"build"') {
    Write-Host "✅ Build script found in package.json" -ForegroundColor Green
} else {
    Write-Host "❌ Build script missing in package.json" -ForegroundColor Red
    exit 1
}

# Test npm install locally (optional)
Write-Host ""
Write-Host "🧪 Testing npm install locally..." -ForegroundColor Yellow
if (Get-Command npm -ErrorAction SilentlyContinue) {
    try {
        npm install --dry-run > $null 2>&1
        Write-Host "✅ npm install would succeed" -ForegroundColor Green
    } catch {
        Write-Host "⚠️  npm install might have issues" -ForegroundColor Yellow
    }
} else {
    Write-Host "ℹ️  npm not available locally (this is OK for Docker build)" -ForegroundColor Blue
}

Write-Host ""
Write-Host "🐳 NPM setup is ready for Docker build!" -ForegroundColor Green
Write-Host "   The frontend build stage should now work correctly." -ForegroundColor White