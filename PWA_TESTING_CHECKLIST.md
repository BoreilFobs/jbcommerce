# PWA Testing Checklist - JB Shop

## 🚀 Deployment Status
**Version:** 1.0.0  
**Date:** December 13, 2025  
**Status:** ✅ Ready for Testing

---

## 📋 Pre-Testing Setup

### 1. Clear Browser Cache
```javascript
// Open browser DevTools Console and run:
localStorage.clear();
sessionStorage.clear();
caches.keys().then(keys => keys.forEach(key => caches.delete(key)));
location.reload();
```

### 2. Required Browser Features
- ✅ Service Worker support
- ✅ Cache API
- ✅ Web App Manifest
- ✅ Online/Offline events

**Recommended Browsers:**
- Chrome 90+ (Desktop & Mobile)
- Edge 90+
- Safari 15+ (iOS)
- Firefox 88+

---

## 🧪 Testing Scenarios

### A. Service Worker Registration

**Test Steps:**
1. Open the website in a fresh browser tab
2. Open DevTools → Application → Service Workers
3. Verify service worker is registered with status "activated"
4. Check Console for: `[PWA] Service Worker registered successfully`

**Expected Results:**
- ✅ Service worker shows as "activated and running"
- ✅ Scope is "/"
- ✅ No registration errors in console

**Screenshot Location:** `Application > Service Workers`

---

### B. Cache Verification

**Test Steps:**
1. Open DevTools → Application → Cache Storage
2. Verify these caches exist:
   - `jbshop-v1.0.0-static`
   - `jbshop-v1.0.0-dynamic`
   - `jbshop-v1.0.0-images`
   - `jbshop-v1.0.0-important`

3. Open `jbshop-v1.0.0-static`
4. Verify core assets are cached:
   - `/` (homepage)
   - `/store` (store page)
   - `/offline` (offline fallback)
   - `/css/bootstrap.min.css`
   - `/js/pwa-init.js`
   - `/manifest.json`
   - `/img/logo.png`
   - `/img/placeholder.svg`

**Expected Results:**
- ✅ All 4 cache types created
- ✅ 15+ core assets in static cache
- ✅ No cache errors in console

---

### C. Offline Functionality

**Test Steps:**
1. Browse the following pages while **ONLINE**:
   - Homepage (/)
   - Store (/store)
   - At least 3 product pages
   - About page
   - Contact page

2. Open DevTools → Network tab
3. Check "Offline" checkbox (or set throttling to "Offline")
4. Try navigating to previously visited pages
5. Try navigating to a new page not yet cached
6. Try reloading the current page

**Expected Results:**
- ✅ Previously visited pages load from cache
- ✅ Images show (or placeholder SVG appears)
- ✅ Navigation works between cached pages
- ✅ Non-cached pages redirect to `/offline` page
- ✅ Offline banner appears at top of page (red)
- ✅ Dynamic sections show overlay with "Connexion internet requise"

---

### D. Connection Status Banner

**Test Steps:**
1. Start with browser ONLINE
2. Go to homepage or store page
3. Open DevTools → Network tab
4. Check "Offline" checkbox
5. Wait 2-3 seconds
6. Uncheck "Offline" checkbox
7. Wait 2-3 seconds

**Expected Results:**
- ✅ Banner appears within 3 seconds when going offline (red gradient)
- ✅ Banner text: "Vous êtes hors ligne"
- ✅ Banner disappears when back online (green "Connexion rétablie")
- ✅ Green banner auto-hides after 3 seconds
- ✅ Page body padding adjusts when banner shown

---

### E. Offline Page

**Test Steps:**
1. Clear all caches
2. Set DevTools to "Offline"
3. Navigate to website
4. You should be redirected to `/offline`
5. Verify offline page elements:
   - Large WiFi-off icon
   - Title: "Pas de Connexion Internet"
   - Info alert with offline capabilities
   - 4 troubleshooting tips
   - "Réessayer" button
   - "Accueil (Hors ligne)" button
   - Connection status badge (pulse animation)

**Expected Results:**
- ✅ Offline page loads even with no cache
- ✅ All visual elements present
- ✅ Connection status updates every 3 seconds
- ✅ Page auto-reloads 2 seconds after going online

---

### F. Dynamic Section Blocking

**Test Steps:**
1. Navigate to a page with forms or dynamic content (e.g., cart, checkout)
2. Identify sections marked with `data-requires-connection="true"`
3. Go offline (DevTools Network → Offline)
4. Observe dynamic sections

**Expected Results:**
- ✅ Semi-transparent overlay appears on dynamic sections
- ✅ Overlay shows spinning icon + "Connexion internet requise"
- ✅ Forms/buttons become unclickable
- ✅ Overlays disappear when back online

**Note:** Currently, no sections are marked. To test, add this attribute manually:
```html
<div class="cart-section" data-requires-connection="true">
    <!-- Cart content -->
</div>
```

---

### G. Image Placeholder

**Test Steps:**
1. Browse product pages while online (cache product pages)
2. Go offline
3. Navigate to a product page with images NOT yet cached
4. Observe product images

**Expected Results:**
- ✅ Cached images load normally
- ✅ Non-cached images show placeholder SVG:
  - Gray background (#f0f0f0)
  - Circle icon with play symbol
  - Text: "Image non disponible"
  - Text: "Mode hors ligne"

---

### H. PWA Installation (Mobile)

**Android Chrome Test Steps:**
1. Open website on Android Chrome
2. Wait for install banner to appear (may take 5-10 seconds)
3. Banner should show:
   - JB Shop logo
   - "Installer JB Shop"
   - "Accès rapide et mode hors ligne"
   - "Installer" button
   - Dismiss (×) button
4. Tap "Installer"
5. Confirm installation
6. App icon appears on home screen

**Expected Results:**
- ✅ Install prompt appears automatically
- ✅ Installation completes successfully
- ✅ App opens in standalone mode (no browser UI)
- ✅ Splash screen shows during launch
- ✅ Offline functionality works in installed app

**iOS Safari Test Steps:**
1. Open website in Safari
2. Tap Share button
3. Tap "Add to Home Screen"
4. Confirm addition
5. Open from home screen

---

### I. Service Worker Update

**Test Steps:**
1. Modify `/public/service-worker.js`
2. Change version: `const VERSION = 'jbshop-v1.0.1';`
3. Reload the page
4. Wait 5-10 seconds
5. Look for update notification

**Expected Results:**
- ✅ Update notification appears (orange gradient)
- ✅ Message: "Mise à jour disponible"
- ✅ "Actualiser" button present
- ✅ Clicking "Actualiser" reloads with new service worker
- ✅ Old caches are deleted
- ✅ New caches are created

---

### J. Background Sync (Orders)

**Test Steps:**
1. Add items to cart while online
2. Proceed to checkout
3. Fill order form
4. Go offline (before submitting)
5. Submit order form
6. Go back online

**Expected Results:**
- ✅ Order is queued in background sync
- ✅ Sync fires when connection restored
- ✅ Order is submitted successfully
- ✅ User receives confirmation

**Note:** This feature requires backend implementation of Background Sync API.

---

## 🔧 Debugging Tools

### Browser DevTools Shortcuts

**Chrome/Edge:**
- Service Workers: `F12` → Application → Service Workers
- Cache Storage: `F12` → Application → Cache Storage
- Network Offline: `F12` → Network → Offline checkbox
- Console Logs: `F12` → Console

**Firefox:**
- Service Workers: `F12` → Application → Service Workers
- Storage: `F12` → Storage → Cache Storage

### Console Commands

**Check PWA Installation:**
```javascript
console.log('Installed:', window.PWA.isInstalled());
console.log('Online:', window.PWA.isOnline());
```

**Manually Update Service Worker:**
```javascript
window.PWA.update();
```

**Trigger Install Prompt:**
```javascript
window.PWA.install();
```

**Check Connection Manager:**
```javascript
console.log(window.connectionManager);
```

**Force Show Install Banner:**
```javascript
localStorage.removeItem('pwa_install_dismissed');
location.reload();
```

---

## 📊 Performance Metrics

### Key Metrics to Monitor

**Lighthouse PWA Audit:**
1. Open DevTools → Lighthouse
2. Select "Progressive Web App"
3. Click "Generate report"
4. Target scores:
   - ✅ PWA: 90+
   - ✅ Performance: 85+
   - ✅ Accessibility: 90+
   - ✅ Best Practices: 90+
   - ✅ SEO: 95+

**Cache Efficiency:**
```javascript
// Run in console to check cache hit rate
caches.open('jbshop-v1.0.0-static').then(cache => {
    cache.keys().then(keys => {
        console.log(`Cached assets: ${keys.length}`);
    });
});
```

---

## 🐛 Common Issues & Solutions

### Issue 1: Service Worker Not Registering
**Symptoms:** No service worker in Application tab  
**Solutions:**
- ✅ Ensure site is served over HTTPS (or localhost)
- ✅ Check Console for registration errors
- ✅ Verify `/service-worker.js` path is correct
- ✅ Clear browser cache and retry

### Issue 2: Caches Not Populating
**Symptoms:** Empty cache storage  
**Solutions:**
- ✅ Check service worker `install` event in console
- ✅ Verify CORE_ASSETS paths are correct
- ✅ Ensure files exist at specified paths
- ✅ Check for CORS errors blocking cache.add()

### Issue 3: Offline Page Not Showing
**Symptoms:** Error page instead of custom offline page  
**Solutions:**
- ✅ Verify `/offline` route exists in `routes/web.php`
- ✅ Check offline.blade.php exists
- ✅ Ensure `/offline` is in CORE_ASSETS
- ✅ Reload service worker (unregister and re-register)

### Issue 4: Connection Banner Not Appearing
**Symptoms:** No banner when going offline  
**Solutions:**
- ✅ Verify `@include('components.connection-status')` in layout
- ✅ Check browser console for JavaScript errors
- ✅ Ensure ConnectionManager initialized: `console.log(window.connectionManager)`
- ✅ Test with DevTools Network offline mode

### Issue 5: Install Banner Not Showing
**Symptoms:** No PWA install prompt  
**Solutions:**
- ✅ Check manifest.json is valid (DevTools → Application → Manifest)
- ✅ Ensure service worker is registered and active
- ✅ Verify app meets PWA installability criteria
- ✅ Check if banner was dismissed (localStorage: `pwa_install_dismissed`)
- ✅ Try in Incognito/Private mode

### Issue 6: Images Not Loading Offline
**Symptoms:** Broken images when offline  
**Solutions:**
- ✅ Images must be visited while online first to be cached
- ✅ Check IMAGE_CACHE in Cache Storage
- ✅ Verify placeholder.svg exists at `/img/placeholder.svg`
- ✅ Check service worker fetch handler for image requests

---

## 📱 Mobile Testing (React Native WebView)

### WebView Configuration

**Required Permissions (AndroidManifest.xml):**
```xml
<uses-permission android:name="android.permission.INTERNET" />
<uses-permission android:name="android.permission.ACCESS_NETWORK_STATE" />
```

**WebView Settings (React Native):**
```javascript
<WebView
  source={{ uri: 'https://jbshop237.com' }}
  cacheEnabled={true}
  cacheMode="LOAD_DEFAULT"
  domStorageEnabled={true}
  javaScriptEnabled={true}
  allowFileAccess={true}
/>
```

**Test Scenarios:**
1. ✅ Load app in WebView
2. ✅ Browse multiple pages
3. ✅ Enable airplane mode
4. ✅ Navigate cached pages
5. ✅ Verify connection banner appears
6. ✅ Return online and verify banner disappears

---

## 📈 Success Criteria

### Minimum Requirements
- ✅ Service worker registers on all pages
- ✅ Core assets cached within 5 seconds of first visit
- ✅ Previously visited pages load offline
- ✅ Offline page displays when no cache available
- ✅ Connection status banner appears/disappears correctly
- ✅ No console errors during normal operation

### Optimal Performance
- ✅ Lighthouse PWA score: 90+
- ✅ First page load (online): < 3 seconds
- ✅ Cached page load (offline): < 1 second
- ✅ Service worker activation: < 2 seconds
- ✅ Banner appears within 3 seconds of going offline
- ✅ Auto-reload within 2 seconds of going online

---

## 🔄 Version Updates

### Current Version: 1.0.0

**To Update Service Worker:**
1. Modify `/public/service-worker.js`
2. Change: `const VERSION = 'jbshop-v1.0.X';`
3. Update cache names:
   ```javascript
   const STATIC_CACHE = `jbshop-v1.0.X-static`;
   const DYNAMIC_CACHE = `jbshop-v1.0.X-dynamic`;
   const IMAGE_CACHE = `jbshop-v1.0.X-images`;
   ```
4. Deploy changes
5. Users will see update notification automatically
6. Clicking "Actualiser" applies update

**Cache Clearing (if needed):**
```javascript
// Run in browser console
caches.keys().then(keys => {
    keys.forEach(key => caches.delete(key));
    console.log('All caches cleared');
});
```

---

## 📝 Maintenance Notes

### Regular Tasks

**Weekly:**
- ✅ Monitor error logs for service worker issues
- ✅ Check cache storage size in production
- ✅ Verify offline functionality still works

**Monthly:**
- ✅ Update service worker version if needed
- ✅ Review and optimize cached assets list
- ✅ Test on latest browser versions

**Quarterly:**
- ✅ Audit PWA features with Lighthouse
- ✅ Review and update offline page content
- ✅ Check for new PWA features/APIs

---

## 🎯 Next Steps After Testing

1. **Mark Dynamic Sections:**
   - Add `data-requires-connection="true"` to forms
   - Add to cart buttons
   - Checkout pages
   - Payment processing sections

2. **Optimize Cache:**
   - Identify most visited pages
   - Pre-cache popular products
   - Adjust cache size limits based on usage

3. **Analytics:**
   - Track PWA installation rate
   - Monitor offline page visits
   - Measure cache hit/miss ratio
   - Track service worker errors

4. **User Education:**
   - Add "Install App" tutorial
   - Create offline mode documentation
   - Explain cache benefits to users

---

## ✅ Final Checklist Before Production

- [ ] All test scenarios passed
- [ ] No console errors on any page
- [ ] Service worker registers successfully
- [ ] Caches populate correctly
- [ ] Offline functionality works
- [ ] Connection banner appears/disappears
- [ ] Offline page loads without internet
- [ ] Image placeholders show correctly
- [ ] PWA installs on mobile devices
- [ ] Lighthouse PWA score ≥ 90
- [ ] Tested in Chrome, Safari, Firefox
- [ ] Tested on Android and iOS
- [ ] WebView integration tested
- [ ] Update mechanism tested
- [ ] Documentation complete

---

**Last Updated:** December 13, 2025  
**Tested By:** [Your Name]  
**Status:** Ready for Production ✅
