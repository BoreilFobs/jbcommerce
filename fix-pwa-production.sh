#!/bin/bash

# Production PWA Fix Script
# Run this on your PRODUCTION server after deployment

echo "🔧 JB Shop PWA Production Fix"
echo "=============================="
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo -e "${RED}❌ Error: Run this from your Laravel project root${NC}"
    exit 1
fi

echo "📁 Step 0: Checking Apache /icons/ alias conflict..."
echo ""

# Check for Apache alias conflict
if [ -f "/etc/apache2/mods-enabled/alias.conf" ]; then
    if grep -q "Alias /icons/" /etc/apache2/mods-enabled/alias.conf 2>/dev/null; then
        echo -e "${YELLOW}⚠️  Apache has a default /icons/ alias that may conflict${NC}"
        echo "   This is likely causing your icons to return 404"
        echo ""
        echo "   To fix this, you have two options:"
        echo ""
        echo "   Option 1: Disable the alias (recommended)"
        echo "   sudo a2disconf serve-cgi-bin"
        echo "   sudo systemctl restart apache2"
        echo ""
        echo "   Option 2: Comment out the alias in alias.conf"
        echo "   sudo nano /etc/apache2/mods-enabled/alias.conf"
        echo "   # Comment out the line: Alias /icons/ \"/usr/share/apache2/icons/\""
        echo "   sudo systemctl restart apache2"
        echo ""
    fi
fi

# Also check autoindex.conf
if [ -f "/etc/apache2/mods-enabled/autoindex.conf" ]; then
    if grep -q "Alias /icons/" /etc/apache2/mods-enabled/autoindex.conf 2>/dev/null; then
        echo -e "${YELLOW}⚠️  Found /icons/ alias in autoindex.conf${NC}"
        echo ""
        echo "   To fix: Edit /etc/apache2/mods-enabled/autoindex.conf"
        echo "   Comment out or remove the Alias /icons/ line"
        echo "   Then: sudo systemctl restart apache2"
        echo ""
    fi
fi

echo "📁 Step 1: Checking if files exist..."
echo ""

# Check critical files
files=(
    "public/manifest.json"
    "public/icons/icon-192x192.png"
    "public/icons/icon-512x512.png"
    "public/service-worker.js"
    "public/js/pwa-init-v2.js"
)

missing=0
for file in "${files[@]}"; do
    if [ -f "$file" ]; then
        echo -e "${GREEN}✅${NC} $file"
    else
        echo -e "${RED}❌${NC} $file - MISSING!"
        missing=1
    fi
done

echo ""

if [ $missing -eq 1 ]; then
    echo -e "${RED}❌ Some files are missing!${NC}"
    echo ""
    echo "Try running:"
    echo "  git pull origin main"
    echo "  git checkout -- public/icons/"
    echo "  git checkout -- public/manifest.json"
    exit 1
fi

echo "📁 Step 2: Checking file permissions..."
echo ""

# Fix permissions
chmod -R 755 public/icons/
chmod 644 public/manifest.json
chmod 644 public/service-worker.js
chmod 644 public/js/pwa-init-v2.js 2>/dev/null || true

echo -e "${GREEN}✅${NC} Permissions fixed"
echo ""

echo "📁 Step 3: Checking storage link..."
echo ""

# Make sure storage link exists
if [ -L "public/storage" ]; then
    echo -e "${GREEN}✅${NC} Storage link exists"
else
    echo -e "${YELLOW}⚠️${NC} Creating storage link..."
    php artisan storage:link
fi

echo ""

echo "📁 Step 4: Clearing caches..."
echo ""

php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

echo ""
echo -e "${GREEN}✅${NC} Caches cleared"
echo ""

echo "📁 Step 5: Testing file accessibility..."
echo ""

# Get the app URL from .env
APP_URL=$(grep APP_URL .env | cut -d '=' -f2 | tr -d '"' | tr -d "'")

if [ -z "$APP_URL" ]; then
    echo -e "${YELLOW}⚠️${NC} Could not determine APP_URL from .env"
    echo "   Please manually test these URLs in your browser:"
else
    echo "Testing URLs (check these in browser):"
fi

echo ""
echo "   ${APP_URL:-https://yoursite.com}/manifest.json"
echo "   ${APP_URL:-https://yoursite.com}/icons/icon-192x192.png"
echo "   ${APP_URL:-https://yoursite.com}/icons/icon-512x512.png"
echo "   ${APP_URL:-https://yoursite.com}/service-worker.js"
echo "   ${APP_URL:-https://yoursite.com}/pwa-test.html"
echo ""

echo "📁 Step 6: Server configuration check..."
echo ""

# Check for .htaccess
if [ -f "public/.htaccess" ]; then
    echo -e "${GREEN}✅${NC} .htaccess exists"
    
    # Check if it has proper rewrite rules
    if grep -q "RewriteEngine" public/.htaccess; then
        echo -e "${GREEN}✅${NC} RewriteEngine found in .htaccess"
    else
        echo -e "${YELLOW}⚠️${NC} RewriteEngine not found - may need configuration"
    fi
else
    echo -e "${YELLOW}⚠️${NC} No .htaccess found - may need to create one"
fi

echo ""

echo "=============================="
echo -e "${GREEN}✅ Production fix complete!${NC}"
echo ""
echo "🔍 Next steps:"
echo ""
echo "1. Test in browser: ${APP_URL:-https://yoursite.com}/pwa-test.html"
echo ""
echo "2. If icons still return 404, check your web server config:"
echo "   - Apache: Ensure mod_rewrite is enabled"
echo "   - Nginx: Check that static files are served correctly"
echo ""
echo "3. Check browser console for [PWA] logs"
echo ""
echo "4. On mobile, clear browser data and reload the site"
echo ""

# Create a simple test file
cat > public/pwa-check.txt << 'EOF'
If you can see this file, static files are being served correctly.
PWA Version: 2.4.0
EOF

echo "📝 Created public/pwa-check.txt - test at ${APP_URL:-https://yoursite.com}/pwa-check.txt"
echo ""
