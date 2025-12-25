# PWA Deployment Fix Guide

## Issue
The PWA install button and modal do not appear on the deployed version, even after pulling the latest changes.

## Root Causes

### 1. **HTTPS Requirement** (CRITICAL)
- PWAs REQUIRE HTTPS on production servers
- Localhost is exempt from this requirement (works over HTTP)
- **Check**: Is your deployed site using `https://` ?
  ```bash
  # Your site must be accessible via HTTPS
  curl -I https://yourdomain.com
  ```

### 2. **Service Worker Registration**
- Service worker must be accessible and served correctly
- Check browser console for errors
- Verify service-worker.js is accessible

### 3. **Browser Cache**
- Old service worker may still be cached
- Need to force clear cache and unregister old service worker

## Solutions Applied

### Code Changes Made:

1. **Enhanced Service Worker Registration** (`public/js/pwa-init.js`)
   - Added automatic unregistration of old service workers
   - Added `updateViaCache: 'none'` to force fresh downloads
   - Better error logging with details
   - Wait for service worker to be ready before proceeding

2. **Improved Install Prompt Handling**
   - More aggressive button/modal display
   - Added iOS detection and manual instructions
   - Added fallback for browsers without `beforeinstallprompt` support
   - Enhanced diagnostic logging

3. **Service Worker Version Bump** (`public/service-worker.js`)
   - Updated from v2.1.0 to v2.2.0
   - Forces cache refresh on all devices
   - Added `clients.claim()` for immediate activation

4. **Better Error Handling**
   - Comprehensive console logging for debugging
   - Shows why install prompt isn't triggering
   - Checks HTTPS, service worker support, manifest accessibility

## Deployment Checklist

### Step 1: Verify HTTPS
```bash
# Check if your site uses HTTPS
curl -I https://yourdomain.com | grep -i "HTTP/"

# Should return: HTTP/2 200 or HTTP/1.1 200
# If you get an error or HTTP (not HTTPS), that's the problem
```

**Solution if no HTTPS:**
- Get an SSL certificate (Let's Encrypt is free)
- Configure your web server (Apache/Nginx) to use HTTPS
- Redirect all HTTP traffic to HTTPS

### Step 2: Clear Service Worker Cache
On the deployed site, open browser console and run:
```javascript
// Check current service workers
navigator.serviceWorker.getRegistrations().then(regs => {
    console.log('Registered service workers:', regs);
    regs.forEach(reg => reg.unregister());
});

// Clear all caches
caches.keys().then(keys => {
    keys.forEach(key => caches.delete(key));
});

// Then reload
location.reload(true);
```

### Step 3: Verify Files Are Deployed
```bash
# Check these URLs return 200 OK:
curl -I https://yourdomain.com/service-worker.js
curl -I https://yourdomain.com/manifest.json
curl -I https://yourdomain.com/js/pwa-init.js
curl -I https://yourdomain.com/icons/icon-192x192.png
curl -I https://yourdomain.com/icons/icon-512x512.png
```

### Step 4: Check Browser Console
Open your deployed site and check the browser console for:
```
[PWA] ✅ Service Worker registered successfully: /
[PWA] ✅ Service Worker is ready!
[PWA] ✅ Install prompt available - App is installable!
```

If you see:
```
[PWA] ❌ Service Worker registration failed
```
Check the error details below it.

### Step 5: Verify PWA Criteria
The browser console will show diagnostic info after 3 seconds:
```
- Protocol: https:  <-- Must be https:
- HTTPS: true       <-- Must be true
- Service Worker: true  <-- Must be true
- Manifest: ✅ Manifest.json accessible  <-- Must be accessible
```

## Common Issues & Fixes

### Issue 1: "beforeinstallprompt not triggered"
**Cause**: PWA criteria not met
**Check**:
1. HTTPS enabled? (required on production)
2. Valid manifest.json?
3. Service worker registered?
4. App not already installed?
5. Browsing in a browser, not in-app WebView?

**Debug**:
```javascript
// Run in console
console.log('HTTPS:', location.protocol === 'https:');
console.log('SW Support:', 'serviceWorker' in navigator);
console.log('Already Installed:', matchMedia('(display-mode: standalone)').matches);
fetch('/manifest.json').then(r => console.log('Manifest:', r.ok));
```

### Issue 2: "Service worker registration failed"
**Cause**: File not accessible or wrong MIME type
**Fix**:
1. Ensure `service-worker.js` exists in `public/` folder
2. Check web server serves `.js` files with `Content-Type: application/javascript`
3. Verify no 404/403 errors in Network tab

### Issue 3: "Old version still showing"
**Cause**: Browser/CDN caching
**Fix**:
1. Clear browser cache (Ctrl+Shift+R or Cmd+Shift+R)
2. Unregister service worker (see Step 2 above)
3. Check if using a CDN - purge CDN cache
4. Add cache-busting headers on server:
```apache
# Apache .htaccess
<FilesMatch "service-worker\.js$">
    Header set Cache-Control "no-store, no-cache, must-revalidate, max-age=0"
</FilesMatch>
```

### Issue 4: "Install button doesn't appear"
**Cause**: Multiple reasons
**Check**:
1. Button element exists in DOM: `document.getElementById('pwa-install-button')`
2. beforeinstallprompt event fired (see console)
3. App not already installed
4. Not in private/incognito mode (PWA disabled)

**Manual Test**:
```javascript
// Force show button for testing
document.getElementById('pwa-install-button').style.display = 'flex';
document.getElementById('pwa-install-button').style.visibility = 'visible';
document.getElementById('pwa-install-button').style.opacity = '1';
```

## Testing on Different Devices

### Desktop (Chrome/Edge)
1. Open site in browser
2. Look for install icon in address bar (⊕ or computer icon)
3. Or use menu: "Install JB Shop..." option
4. Check browser console for PWA logs

### Android (Chrome)
1. Open site in Chrome browser
2. Banner should appear at bottom: "Add JB Shop to Home screen"
3. Or tap menu (⋮) → "Install app" or "Add to Home screen"
4. Icon will appear on home screen

### iOS (Safari)
1. Open site in Safari
2. Tap Share button (box with arrow)
3. Scroll and tap "Add to Home Screen"
4. Note: iOS doesn't support `beforeinstallprompt`, so manual instructions will show

## Server Configuration

### Apache (.htaccess)
```apache
# Force HTTPS
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Service Worker headers
<FilesMatch "service-worker\.js$">
    Header set Cache-Control "no-store, no-cache, must-revalidate, max-age=0"
    Header set Content-Type "application/javascript"
    Header set Service-Worker-Allowed "/"
</FilesMatch>

# Manifest headers
<FilesMatch "manifest\.json$">
    Header set Content-Type "application/manifest+json"
    Header set Cache-Control "public, max-age=604800"
</FilesMatch>
```

### Nginx
```nginx
# Force HTTPS
server {
    listen 80;
    server_name yourdomain.com;
    return 301 https://$server_name$request_uri;
}

# Service Worker headers
location = /service-worker.js {
    add_header Cache-Control "no-store, no-cache, must-revalidate, max-age=0";
    add_header Content-Type "application/javascript";
    add_header Service-Worker-Allowed "/";
}

# Manifest headers
location = /manifest.json {
    add_header Content-Type "application/manifest+json";
    add_header Cache-Control "public, max-age=604800";
}
```

## Verification Tools

### 1. Chrome DevTools
```
1. Open DevTools (F12)
2. Go to "Application" tab
3. Check:
   - Manifest: Should show all details
   - Service Workers: Should show "activated and running"
   - Storage: Check caches
```

### 2. Lighthouse Audit
```
1. Open Chrome DevTools (F12)
2. Go to "Lighthouse" tab
3. Select "Progressive Web App"
4. Click "Generate report"
5. Target: 90+ score
```

### 3. PWA Install Criteria
In Chrome DevTools → Console:
```javascript
// Check all PWA criteria
const checks = {
    https: location.protocol === 'https:' || location.hostname === 'localhost',
    sw: 'serviceWorker' in navigator,
    manifest: document.querySelector('link[rel="manifest"]') !== null,
    notInstalled: !matchMedia('(display-mode: standalone)').matches,
    notWebView: !navigator.userAgent.includes('wv')
};
console.table(checks);
// All should be true
```

## Quick Fix Commands

### For the Server:
```bash
# Deploy latest changes
git pull origin main

# Clear application cache (Laravel)
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Rebuild assets if using build process
npm run build  # or npm run production
```

### For Testing Locally First:
```bash
# Start Laravel server
php artisan serve

# Open in browser
# http://localhost:8000

# Should see PWA install button immediately
```

## Success Indicators

✅ **Browser console shows:**
```
[PWA] ✅ Service Worker registered successfully
[PWA] ✅ Service Worker is ready!
[PWA] ✅ Install prompt available - App is installable!
[PWA] 📱 Showing install button
```

✅ **Visual indicators:**
- Floating install button visible in bottom-right corner
- Mobile: Modal appears after 2 seconds
- Desktop: Install button visible
- Chrome: Install icon appears in address bar

✅ **Lighthouse PWA score:** 90+ points

## Still Not Working?

1. **Check browser console for RED errors** - these are blocking issues
2. **Verify HTTPS is actually enabled** - use `curl -I https://yourdomain.com`
3. **Test in different browser** - Chrome, Edge, Firefox, Safari
4. **Clear everything and start fresh:**
   ```javascript
   // In browser console
   navigator.serviceWorker.getRegistrations().then(r => r.forEach(reg => reg.unregister()));
   caches.keys().then(k => k.forEach(key => caches.delete(key)));
   localStorage.clear();
   location.reload(true);
   ```

5. **Check if you're in an in-app browser** - Open in actual Chrome/Safari app
6. **Verify not in incognito mode** - PWA features disabled in private browsing

## Contact for Help

If still having issues, provide these details:
1. Browser console logs (all messages starting with [PWA])
2. Screenshot of Chrome DevTools → Application → Manifest
3. Network tab showing service-worker.js request/response
4. Your domain URL (to verify HTTPS)
5. Browser and device info

---

**Files Modified:**
- `public/js/pwa-init.js` - Enhanced registration and install handling
- `public/service-worker.js` - Version 2.2.0 with better caching
- This guide: `PWA_DEPLOYMENT_FIX.md`
