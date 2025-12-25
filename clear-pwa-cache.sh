#!/bin/bash

# PWA Cache Busting Script for Production Deployment
# Run this on your production server after deploying PWA updates

echo "🔄 JB Shop PWA Cache Busting"
echo "============================="
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Step 1: Clear Laravel caches
echo -e "${BLUE}Step 1: Clearing Laravel caches...${NC}"
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
echo -e "${GREEN}✅ Laravel caches cleared${NC}"
echo ""

# Step 2: Update service worker version
echo -e "${BLUE}Step 2: Service Worker version check...${NC}"
SW_VERSION=$(grep -oP "CACHE_VERSION = '\K[^']+" public/service-worker.js)
echo "   Current version: $SW_VERSION"
echo -e "${GREEN}✅ Make sure this version is newer than production!${NC}"
echo ""

# Step 3: Clear browser instructions
echo -e "${BLUE}Step 3: Browser cache clearing instructions...${NC}"
echo ""
echo "📱 Tell users to clear browser cache:"
echo ""
echo "   Android Chrome:"
echo "   ---------------"
echo "   1. Open Chrome Settings"
echo "   2. Privacy → Clear browsing data"
echo "   3. Select 'Cached images and files'"
echo "   4. Click 'Clear data'"
echo ""
echo "   OR use hard refresh:"
echo "   - Pull down from top of page and hold"
echo "   - Tap refresh icon"
echo ""
echo "   Desktop Chrome/Edge:"
echo "   --------------------"
echo "   - Press Ctrl+Shift+R (Windows/Linux)"
echo "   - Press Cmd+Shift+R (Mac)"
echo ""
echo "   OR open DevTools:"
echo "   - Press F12"
echo "   - Right-click refresh button"
echo "   - Select 'Empty Cache and Hard Reload'"
echo ""

# Step 4: Service worker unregister command
echo -e "${BLUE}Step 4: JavaScript command to force update...${NC}"
echo ""
echo "Have users open browser console (F12) and paste:"
echo ""
echo -e "${YELLOW}// Clear service workers${NC}"
echo -e "${YELLOW}navigator.serviceWorker.getRegistrations().then(regs => {${NC}"
echo -e "${YELLOW}  regs.forEach(reg => reg.unregister());${NC}"
echo -e "${YELLOW}});${NC}"
echo ""
echo -e "${YELLOW}// Clear all caches${NC}"
echo -e "${YELLOW}caches.keys().then(keys => {${NC}"
echo -e "${YELLOW}  keys.forEach(key => caches.delete(key));${NC}"
echo -e "${YELLOW}});${NC}"
echo ""
echo -e "${YELLOW}// Reload page${NC}"
echo -e "${YELLOW}setTimeout(() => location.reload(true), 1000);${NC}"
echo ""

# Step 5: Verification
echo -e "${BLUE}Step 5: Verification checklist...${NC}"
echo ""
echo "After deploying, verify:"
echo "  □ HTTPS is enabled"
echo "  □ Service worker registered (check console)"
echo "  □ Manifest.json accessible"
echo "  □ Install prompt appears"
echo "  □ Modal shows on mobile after 2 seconds"
echo ""

# Step 6: Quick test URLs
echo -e "${BLUE}Step 6: Test URLs...${NC}"
echo ""
echo "Test these URLs on production:"
echo "  • https://yourdomain.com/pwa-test.html"
echo "  • https://yourdomain.com/manifest.json"
echo "  • https://yourdomain.com/service-worker.js"
echo "  • https://yourdomain.com/icons/icon-192x192.png"
echo ""

# Step 7: Console debugging
echo -e "${BLUE}Step 7: Debug commands...${NC}"
echo ""
echo "Check PWA status in console:"
echo ""
echo -e "${YELLOW}// Check service worker${NC}"
echo -e "${YELLOW}navigator.serviceWorker.getRegistrations().then(r => console.log(r));${NC}"
echo ""
echo -e "${YELLOW}// Check caches${NC}"
echo -e "${YELLOW}caches.keys().then(k => console.log(k));${NC}"
echo ""
echo -e "${YELLOW}// Check manifest${NC}"
echo -e "${YELLOW}fetch('/manifest.json').then(r => r.json()).then(m => console.log(m));${NC}"
echo ""
echo -e "${YELLOW}// Force show install modal (for testing)${NC}"
echo -e "${YELLOW}sessionStorage.removeItem('pwa_modal_shown_this_session');${NC}"
echo -e "${YELLOW}localStorage.removeItem('pwa_install_remind_later');${NC}"
echo -e "${YELLOW}location.reload();${NC}"
echo ""

echo "============================="
echo -e "${GREEN}✅ Cache busting complete!${NC}"
echo ""
echo "Next steps:"
echo "1. Deploy code to production server"
echo "2. Run this script on production"
echo "3. Have users hard refresh (Ctrl+Shift+R)"
echo "4. Check browser console for [PWA] logs"
echo "5. Visit /pwa-test.html to verify all requirements"
echo ""
echo "For persistent cache issues, use the console commands above."
