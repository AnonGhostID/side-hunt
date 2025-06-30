#!/bin/bash

echo "🔍 Testing NPM Setup for Docker Build"
echo "====================================="

# Check if package.json exists
if [ -f "package.json" ]; then
    echo "✅ package.json found"
else
    echo "❌ package.json missing"
    exit 1
fi

# Check if package-lock.json exists
if [ -f "package-lock.json" ]; then
    echo "✅ package-lock.json found"
else
    echo "⚠️  package-lock.json missing (will use npm install)"
fi

# Check if build script exists
if grep -q '"build"' package.json; then
    echo "✅ Build script found in package.json"
else
    echo "❌ Build script missing in package.json"
    exit 1
fi

# Test npm install locally (optional)
echo ""
echo "🧪 Testing npm install locally..."
if command -v npm &> /dev/null; then
    npm install --dry-run > /dev/null 2>&1
    if [ $? -eq 0 ]; then
        echo "✅ npm install would succeed"
    else
        echo "⚠️  npm install might have issues"
    fi
else
    echo "ℹ️  npm not available locally (this is OK for Docker build)"
fi

echo ""
echo "🐳 NPM setup is ready for Docker build!"
echo "   The frontend build stage should now work correctly."