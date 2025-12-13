# PWA Cache Management Guide - JB Shop

## 🎯 Quick Reference

### Cache Structure
```
jbshop-v1.0.0-static      → Core app shell (permanent)
jbshop-v1.0.0-dynamic     → API responses (max 50 items)
jbshop-v1.0.0-images      → Product images (max 100 items)
jbshop-v1.0.0-important   → User-specific pages (background)
```

---

## 📦 What Gets Cached

### Static Cache (Permanent)
- **Pages:** /, /store, /offline
- **CSS:** Bootstrap, custom styles, animations
- **JS:** Main scripts, PWA init
- **Assets:** Manifest, logo, placeholder SVG
- **Size:** ~2-5 MB
- **Lifetime:** Until service worker update

### Dynamic Cache (LRU - Least Recently Used)
- **Content:** Page HTML, API responses
- **Limit:** 50 items
- **Strategy:** Network First with cache fallback
- **Auto-cleanup:** Removes oldest when limit reached

### Image Cache (LRU)
- **Content:** Product images, avatars, thumbnails
- **Limit:** 100 images
- **Strategy:** Cache First for performance
- **Fallback:** placeholder.svg
- **Auto-cleanup:** Removes oldest when limit reached

---

## ⚙️ Cache Strategies

### 1. Cache First (Static Assets)
```
User Request → Cache → [HIT] Return cached
                     → [MISS] Fetch → Cache → Return
```
**Best for:** CSS, JS, fonts, logos  
**Benefit:** Instant loading  
**Drawback:** May serve stale content

### 2. Network First (Dynamic Content)
```
User Request → Network → [SUCCESS] Cache → Return
                       → [FAIL] Cache → Return cached
```
**Best for:** HTML pages, API data  
**Benefit:** Fresh content when online  
**Drawback:** Slower when online, fast when offline

### 3. Cache First (Images)
```
User Request → Cache → [HIT] Return cached
                     → [MISS] Fetch → Cache → Return
                     → [FAIL] Return placeholder.svg
```
**Best for:** Product images, avatars  
**Benefit:** Fast image loading  
**Fallback:** Graceful degradation with placeholder

### 4. Stale While Revalidate (Default)
```
User Request → Cache → Return cached immediately
            → Network → Update cache in background
```
**Best for:** Less critical resources  
**Benefit:** Instant response + fresh updates  
**Use case:** Secondary assets

---

## 🛠️ Manual Cache Control

### Clear All Caches (Browser Console)
```javascript
caches.keys().then(keys => {
    keys.forEach(key => caches.delete(key));
    console.log('✅ All caches cleared');
});
```

### Clear Specific Cache
```javascript
caches.delete('jbshop-v1.0.0-dynamic')
    .then(() => console.log('✅ Dynamic cache cleared'));
```

### List All Cached URLs
```javascript
caches.open('jbshop-v1.0.0-static').then(cache => {
    cache.keys().then(keys => {
        console.log('Cached URLs:', keys.map(k => k.url));
    });
});
```

### Add URL to Cache Manually
```javascript
caches.open('jbshop-v1.0.0-static').then(cache => {
    cache.add('/new-page');
});
```

### Remove Specific URL from Cache
```javascript
caches.open('jbshop-v1.0.0-images').then(cache => {
    cache.delete('/img/old-product.jpg');
});
```

### Check Cache Size
```javascript
async function getCacheSize(cacheName) {
    const cache = await caches.open(cacheName);
    const keys = await cache.keys();
    let size = 0;
    
    for (const request of keys) {
        const response = await cache.match(request);
        const blob = await response.blob();
        size += blob.size;
    }
    
    console.log(`${cacheName}: ${(size / 1024 / 1024).toFixed(2)} MB (${keys.length} items)`);
}

// Check all caches
['jbshop-v1.0.0-static', 'jbshop-v1.0.0-dynamic', 'jbshop-v1.0.0-images'].forEach(getCacheSize);
```

---

## 🔄 Service Worker Updates

### Update Flow
```
1. New SW file detected
   ↓
2. New SW installed (waits in "waiting" state)
   ↓
3. Update notification shown to user
   ↓
4. User clicks "Actualiser"
   ↓
5. New SW activates (skipWaiting)
   ↓
6. Old caches deleted
   ↓
7. New caches created
   ↓
8. Page reloads
```

### Manual Update Check
```javascript
navigator.serviceWorker.getRegistration().then(reg => {
    reg.update();
    console.log('✅ Checking for updates...');
});
```

### Force Update (Skip Waiting)
```javascript
navigator.serviceWorker.getRegistration().then(reg => {
    reg.waiting?.postMessage({ type: 'SKIP_WAITING' });
});
```

### Unregister Service Worker
```javascript
navigator.serviceWorker.getRegistration().then(reg => {
    reg.unregister().then(() => {
        console.log('✅ Service worker unregistered');
        caches.keys().then(keys => keys.forEach(k => caches.delete(k)));
    });
});
```

---

## 📊 Cache Monitoring

### Check What's Cached (DevTools)
1. Open DevTools (`F12`)
2. Go to **Application** tab
3. Expand **Cache Storage** in left sidebar
4. Click on cache name to view contents
5. Right-click entry → Delete to remove

### Service Worker Status
1. DevTools → **Application** → **Service Workers**
2. Check status: "activated and running"
3. Click "Update" to force update
4. Click "Unregister" to remove

### Network Activity (Offline Testing)
1. DevTools → **Network** tab
2. Check "Offline" checkbox
3. Reload page
4. Look for "(from ServiceWorker)" in Size column
5. Indicates resource served from cache

---

## 🚨 Troubleshooting

### Problem: Cache Not Updating
**Symptoms:** Old content still showing after update

**Solution 1: Hard Refresh**
```
Windows: Ctrl + Shift + R
Mac: Cmd + Shift + R
```

**Solution 2: Clear Cache & Hard Reload**
1. Right-click reload button
2. Select "Empty Cache and Hard Reload"

**Solution 3: Manual Cache Clear**
```javascript
caches.keys().then(keys => {
    keys.forEach(key => caches.delete(key));
    location.reload();
});
```

### Problem: Service Worker Not Installing
**Symptoms:** No SW in Application tab

**Checklist:**
- ✅ HTTPS or localhost?
- ✅ Browser supports Service Workers?
- ✅ `/service-worker.js` exists and accessible?
- ✅ No JavaScript errors in Console?
- ✅ CORS headers correct?

### Problem: Quota Exceeded
**Symptoms:** "QuotaExceededError" in console

**Solutions:**
1. Reduce MAX_IMAGE_CACHE_SIZE in service-worker.js
2. Clear old caches manually
3. Implement aggressive cache cleanup
```javascript
// Add to service worker
const MAX_CACHE_SIZE_MB = 50;
async function enforceQuota() {
    const cacheNames = await caches.keys();
    let totalSize = 0;
    
    for (const name of cacheNames) {
        const cache = await caches.open(name);
        const keys = await cache.keys();
        
        for (const request of keys) {
            const response = await cache.match(request);
            const blob = await response.blob();
            totalSize += blob.size;
        }
    }
    
    if (totalSize > MAX_CACHE_SIZE_MB * 1024 * 1024) {
        // Clear dynamic and image caches
        await caches.delete('jbshop-v1.0.0-dynamic');
        await caches.delete('jbshop-v1.0.0-images');
    }
}
```

### Problem: Images Not Loading Offline
**Symptoms:** Broken images or no placeholder

**Solutions:**
1. Ensure images visited while online first
2. Check placeholder.svg exists: `/img/placeholder.svg`
3. Verify IMAGE_CACHE in Cache Storage
4. Check service worker fetch handler:
```javascript
// Should see this in SW console
console.log('[SW] Image fetch failed, returning placeholder');
```

---

## 🎛️ Configuration Options

### Adjust Cache Limits
Edit `/public/service-worker.js`:

```javascript
// Current limits
const MAX_DYNAMIC_CACHE_SIZE = 50;   // API responses
const MAX_IMAGE_CACHE_SIZE = 100;    // Images

// Recommended ranges:
// Low storage devices: 25 dynamic, 50 images
// Medium devices: 50 dynamic, 100 images (default)
// High storage: 100 dynamic, 200 images
```

### Add New Pages to Static Cache
```javascript
const CORE_ASSETS = [
    '/',
    '/store',
    '/offline',
    '/about',        // ← Add here
    '/contact',      // ← Add here
    // ... other assets
];
```

### Exclude URLs from Caching
```javascript
// Add to fetch event handler
const CACHE_BLACKLIST = [
    '/admin/',
    '/login',
    '/logout',
    '/api/analytics'
];

self.addEventListener('fetch', event => {
    const url = new URL(event.request.url);
    
    // Don't cache blacklisted URLs
    if (CACHE_BLACKLIST.some(path => url.pathname.startsWith(path))) {
        event.respondWith(fetch(event.request));
        return;
    }
    
    // ... rest of fetch handler
});
```

---

## 📈 Best Practices

### 1. Cache Versioning
- ✅ Update VERSION when changing cached assets
- ✅ Old caches automatically deleted on activation
- ✅ Use semantic versioning: `v1.0.0`, `v1.1.0`, etc.

### 2. Cache Efficiency
- ✅ Only cache essential resources in static cache
- ✅ Use dynamic cache for user-specific content
- ✅ Implement cache limits to avoid bloat
- ✅ Clear old caches on SW update

### 3. Offline Strategy
- ✅ Cache critical pages (homepage, store, offline)
- ✅ Use Network First for dynamic content
- ✅ Provide meaningful offline fallback
- ✅ Show connection status to users

### 4. Performance
- ✅ Pre-cache core assets on SW install
- ✅ Use Cache First for static assets
- ✅ Lazy-cache user navigation
- ✅ Compress assets before caching

### 5. User Experience
- ✅ Show update notification when new SW available
- ✅ Don't force updates (let user choose)
- ✅ Indicate when content is cached
- ✅ Provide "Refresh" option for stale content

---

## 🔍 Cache Inspection Tools

### DevTools Commands

**List All Caches:**
```javascript
caches.keys().then(console.log);
```

**Count Items in Cache:**
```javascript
caches.open('jbshop-v1.0.0-static')
    .then(cache => cache.keys())
    .then(keys => console.log(`Items: ${keys.length}`));
```

**Find URL in Cache:**
```javascript
async function findInCache(url) {
    const cacheNames = await caches.keys();
    
    for (const name of cacheNames) {
        const cache = await caches.open(name);
        const match = await cache.match(url);
        
        if (match) {
            console.log(`Found in ${name}`);
            return match;
        }
    }
    
    console.log('Not found in any cache');
}

findInCache('/store');
```

---

## 📱 Mobile Considerations

### Storage Limits by Platform

**Android Chrome:**
- Quota: ~50% of available storage
- Typical: 100-500 MB
- Eviction: LRU when storage critical

**iOS Safari:**
- Quota: ~50 MB per origin
- Typical: 20-50 MB
- Eviction: After 7 days of no use

**Best Practice:**
- Keep total cache < 25 MB for iOS compatibility
- Prioritize most important assets
- Implement aggressive cleanup

### WebView Storage
```javascript
// React Native WebView
<WebView
  cacheEnabled={true}
  cacheMode="LOAD_DEFAULT"
  domStorageEnabled={true}
/>
```

---

## 🎯 Production Checklist

- [ ] Cache limits configured appropriately
- [ ] Core assets list up to date
- [ ] Service worker version incremented
- [ ] Update notification working
- [ ] Old cache cleanup verified
- [ ] Offline fallback tested
- [ ] Cache hit rate monitored
- [ ] Storage quota respected
- [ ] Mobile storage limits considered
- [ ] Cache invalidation strategy defined

---

**Version:** 1.0.0  
**Last Updated:** December 13, 2025  
**Maintained By:** JB Shop Development Team
