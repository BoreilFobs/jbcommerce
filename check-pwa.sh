#!/bin/bash

# PWA Deployment and Testing Script
# This script helps verify PWA files and clear caches

echo "🔍 JB Shop PWA Deployment Check"
echo "================================"
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if files exist
echo "📁 Checking PWA files..."
files=(
    "public/service-worker.js"
    "public/manifest.json"
    "public/js/pwa-init-v2.js"
    "public/icons/icon-192x192.png"
    "public/icons/icon-512x512.png"
    "public/icons/icon-192x192-maskable.png"
    "public/icons/icon-512x512-maskable.png"
)

all_exist=true
for file in "${files[@]}"; do
    if [ -f "$file" ]; then
        echo -e "${GREEN}✅${NC} $file"
    else
        echo -e "${RED}❌${NC} $file - NOT FOUND"
        all_exist=false
    fi
done

echo ""

# Check JavaScript syntax
echo "🔧 Checking JavaScript syntax..."
if node -c public/js/pwa-init-v2.js 2>/dev/null; then
    echo -e "${GREEN}✅${NC} pwa-init-v2.js syntax valid"
else
    echo -e "${RED}❌${NC} pwa-init-v2.js has syntax errors"
    all_exist=false
fi

if node -c public/service-worker.js 2>/dev/null; then
    echo -e "${GREEN}✅${NC} service-worker.js syntax valid"
else
    echo -e "${RED}❌${NC} service-worker.js has syntax errors"
    all_exist=false
fi

echo ""

# Check service worker version
echo "📋 Service Worker version:"
version=$(grep -oP "CACHE_VERSION = '\K[^']+" public/service-worker.js)
echo "   Current version: $version"

echo ""

# Laravel cache clearing
echo "🧹 Clearing Laravel caches..."
php artisan cache:clear > /dev/null 2>&1 && echo -e "${GREEN}✅${NC} Application cache cleared" || echo -e "${YELLOW}⚠${NC}  Cache clear skipped"
php artisan config:clear > /dev/null 2>&1 && echo -e "${GREEN}✅${NC} Config cache cleared" || echo -e "${YELLOW}⚠${NC}  Config clear skipped"
php artisan view:clear > /dev/null 2>&1 && echo -e "${GREEN}✅${NC} View cache cleared" || echo -e "${YELLOW}⚠${NC}  View clear skipped"

echo ""

# Check if server is running
echo "🌐 Server status:"
if lsof -Pi :8000 -sTCP:LISTEN -t >/dev/null 2>&1; then
    echo -e "${GREEN}✅${NC} Development server is running on port 8000"
    echo "   Visit: http://localhost:8000"
else
    echo -e "${YELLOW}⚠${NC}  Development server not running"
    echo "   Start with: php artisan serve"
fi

echo ""

# Summary
if [ "$all_exist" = true ]; then
    echo -e "${GREEN}✅ All PWA files present and valid!${NC}"
    echo ""
    echo "📱 Next steps:"
    echo "   1. Deploy these changes to your production server"
    echo "   2. Ensure HTTPS is enabled on production"
    echo "   3. Clear browser cache on production (Ctrl+Shift+R)"
    echo "   4. Check browser console for [PWA] messages"
    echo ""
    echo "📖 See PWA_DEPLOYMENT_FIX.md for detailed deployment guide"
else
    echo -e "${RED}❌ Some files are missing or invalid${NC}"
    echo "   Check the errors above"
fi

echo ""
echo "================================"
echo "For more help, see PWA_DEPLOYMENT_FIX.md"
