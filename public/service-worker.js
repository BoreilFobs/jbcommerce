// Service Worker for JB Shop - PWA with Offline Support
// Version 2.0.0 - Enhanced caching with real-time updates

const CACHE_VERSION = 'jbshop-v2.0.0';
const STATIC_CACHE = `${CACHE_VERSION}-static`;
const DYNAMIC_CACHE = `${CACHE_VERSION}-dynamic`;
const IMAGE_CACHE = `${CACHE_VERSION}-images`;

// Cache expiration times (in milliseconds)
const CACHE_EXPIRATION = {
    dynamic: 5 * 60 * 1000,      // 5 minutes for dynamic content
    api: 2 * 60 * 1000,          // 2 minutes for API calls
    images: 7 * 24 * 60 * 60 * 1000, // 7 days for images
    static: 30 * 24 * 60 * 60 * 1000 // 30 days for static assets
};

// Core assets to cache immediately (shell architecture)
const CORE_ASSETS = [
    '/',
    '/store',
    '/offline',
    '/css/bootstrap.min.css',
    '/css/style.css',
    '/css/store-filters.css',
    '/css/mobile-responsive.css',
    '/css/related-products.css',
    '/js/main.js',
    '/js/pwa-init.js',
    '/lib/animate/animate.min.css',
    '/lib/owlcarousel/assets/owl.carousel.min.css',
    '/manifest.json',
    '/img/logo.png',
    '/img/placeholder.svg'
];

// API endpoints that need network-first strategy with cache invalidation
const API_ENDPOINTS = [
    '/api/',
    '/cart/',
    '/checkout/',
    '/orders/',
    '/fcm/token'
];

// Dynamic content that needs frequent updates
const DYNAMIC_CONTENT = [
    '/orders',
    '/cart',
    '/wishlist',
    '/profile'
];

// Maximum cache sizes
const MAX_DYNAMIC_CACHE_SIZE = 50;
const MAX_IMAGE_CACHE_SIZE = 100;

// Online status detection
let isOnline = true;
self.addEventListener('online', () => {
    isOnline = true;
    console.log('[Service Worker] Device is online');
    // Invalidate dynamic cache when coming back online
    invalidateDynamicCache();
});

self.addEventListener('offline', () => {
    isOnline = false;
    console.log('[Service Worker] Device is offline');
});

// Install event - cache core assets
self.addEventListener('install', (event) => {
    console.log('[Service Worker] Installing...');
    
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then((cache) => {
                console.log('[Service Worker] Caching core assets');
                return cache.addAll(CORE_ASSETS);
            })
            .then(() => self.skipWaiting())
            .catch((error) => {
                console.error('[Service Worker] Failed to cache core assets:', error);
            })
    );
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
    console.log('[Service Worker] Activating...');
    
    event.waitUntil(
        caches.keys()
            .then((cacheNames) => {
                return Promise.all(
                    cacheNames
                        .filter((cacheName) => {
                            return cacheName.startsWith('jbshop-') && 
                                   cacheName !== STATIC_CACHE && 
                                   cacheName !== DYNAMIC_CACHE &&
                                   cacheName !== IMAGE_CACHE;
                        })
                        .map((cacheName) => {
                            console.log('[Service Worker] Deleting old cache:', cacheName);
                            return caches.delete(cacheName);
                        })
                );
            })
            .then(() => self.clients.claim())
    );
});

// Fetch event - implement caching strategies with real-time updates
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);
    
    // Skip non-GET requests
    if (request.method !== 'GET') {
        return;
    }
    
    // Skip chrome extensions and other protocols
    if (!url.protocol.startsWith('http')) {
        return;
    }
    
    // Skip FCM and Firebase requests (handle separately)
    if (url.pathname.includes('firebase') || url.hostname.includes('googleapis')) {
        event.respondWith(fetch(request));
        return;
    }
    
    // Handle different types of requests with appropriate strategies
    
    // 1. Core static assets - Cache First with expiration check
    if (CORE_ASSETS.some(asset => url.pathname === asset || url.pathname.startsWith(asset))) {
        event.respondWith(cacheFirstWithExpiration(request, STATIC_CACHE, CACHE_EXPIRATION.static));
        return;
    }
    
    // 2. Images - Cache First with expiration and fallback
    if (request.destination === 'image' || url.pathname.match(/\.(jpg|jpeg|png|gif|svg|webp|ico)$/i)) {
        event.respondWith(cacheFirstImages(request));
        return;
    }
    
    // 3. API calls - Network First with short cache expiration
    if (API_ENDPOINTS.some(endpoint => url.pathname.startsWith(endpoint))) {
        event.respondWith(networkFirstWithExpiration(request, CACHE_EXPIRATION.api));
        return;
    }
    
    // 4. Dynamic content (orders, cart, etc.) - Network First with immediate update
    if (DYNAMIC_CONTENT.some(endpoint => url.pathname.startsWith(endpoint))) {
        event.respondWith(networkFirstWithImmediateUpdate(request));
        return;
    }
    
    // 5. HTML pages - Network First with offline fallback and expiration
    if (request.headers.get('accept')?.includes('text/html')) {
        event.respondWith(networkFirstWithOfflineAndExpiration(request));
        return;
    }
    
    // 6. Everything else - Stale While Revalidate with expiration check
    event.respondWith(staleWhileRevalidateWithExpiration(request));
});

// Helper: Check if cached response is expired
function isCacheExpired(cachedResponse, maxAge) {
    if (!cachedResponse) return true;
    
    const cachedDate = cachedResponse.headers.get('sw-cache-date');
    if (!cachedDate) return true;
    
    const age = Date.now() - parseInt(cachedDate, 10);
    return age > maxAge;
}

// Helper: Add cache date to response
function addCacheDate(response) {
    const clonedResponse = response.clone();
    const headers = new Headers(clonedResponse.headers);
    headers.set('sw-cache-date', Date.now().toString());
    
    return new Response(clonedResponse.body, {
        status: clonedResponse.status,
        statusText: clonedResponse.statusText,
        headers: headers
    });
}

// Cache First with Expiration Check
async function cacheFirstWithExpiration(request, cacheName = STATIC_CACHE, maxAge = CACHE_EXPIRATION.static) {
    const cachedResponse = await caches.match(request);
    
    // Check if cache is still valid
    if (cachedResponse && !isCacheExpired(cachedResponse, maxAge)) {
        // Cache is valid, but update in background if online
        if (isOnline) {
            fetch(request).then(networkResponse => {
                if (networkResponse.ok) {
                    caches.open(cacheName).then(cache => {
                        cache.put(request, addCacheDate(networkResponse));
                    });
                }
            }).catch(() => {});
        }
        return cachedResponse;
    }
    
    // Cache expired or doesn't exist, fetch from network
    try {
        const networkResponse = await fetch(request);
        
        if (networkResponse.ok) {
            const cache = await caches.open(cacheName);
            cache.put(request, addCacheDate(networkResponse.clone()));
        }
        
        return networkResponse;
    } catch (error) {
        // Network failed, return expired cache if available
        if (cachedResponse) {
            console.log('[Service Worker] Returning expired cache for:', request.url);
            return cachedResponse;
        }
        console.error('[Service Worker] Cache First with Expiration failed:', error);
        throw error;
    }
}

// Cache First for Images with fallback
async function cacheFirstImages(request) {
    const cachedResponse = await caches.match(request);
    
    if (cachedResponse) {
        return cachedResponse;
    }
    
    try {
        const networkResponse = await fetch(request);
        
        if (networkResponse.ok) {
            const cache = await caches.open(IMAGE_CACHE);
            cache.put(request, networkResponse.clone());
            
            // Limit cache size
            limitCacheSize(IMAGE_CACHE, MAX_IMAGE_CACHE_SIZE);
        }
        
        return networkResponse;
    } catch (error) {
        // Return placeholder SVG if available
        const placeholder = await caches.match('/img/placeholder.svg');
        if (placeholder) return placeholder;
        
        // Inline SVG fallback
        return new Response(
            '<svg width="400" height="400" xmlns="http://www.w3.org/2000/svg"><rect width="400" height="400" fill="#f0f0f0"/><g transform="translate(200, 150)"><circle cx="0" cy="0" r="50" fill="#cccccc" opacity="0.5"/><path d="M -20,-10 L -20,10 L 10,0 Z" fill="#999999" opacity="0.7"/></g><text x="200" y="260" text-anchor="middle" font-family="Arial, sans-serif" font-size="20" fill="#999999">Image non disponible</text><text x="200" y="290" text-anchor="middle" font-family="Arial, sans-serif" font-size="16" fill="#bbbbbb">Mode hors ligne</text></svg>',
            { 
                status: 200,
                statusText: 'OK',
                headers: { 'Content-Type': 'image/svg+xml' }
            }
        );
    }
}

// Network First with Expiration (for API calls)
async function networkFirstWithExpiration(request, maxAge = CACHE_EXPIRATION.api) {
    try {
        const networkResponse = await fetch(request);
        
        if (networkResponse.ok) {
            const cache = await caches.open(DYNAMIC_CACHE);
            cache.put(request, addCacheDate(networkResponse.clone()));
            
            // Limit cache size
            limitCacheSize(DYNAMIC_CACHE, MAX_DYNAMIC_CACHE_SIZE);
        }
        
        return networkResponse;
    } catch (error) {
        const cachedResponse = await caches.match(request);
        
        // Only use cache if not expired
        if (cachedResponse && !isCacheExpired(cachedResponse, maxAge)) {
            console.log('[Service Worker] Using cached API response (not expired)');
            return cachedResponse;
        }
        
        // Return expired cache with warning header
        if (cachedResponse) {
            console.log('[Service Worker] Using expired cached API response (offline)');
            const headers = new Headers(cachedResponse.headers);
            headers.set('X-Cache-Status', 'EXPIRED');
            return new Response(cachedResponse.body, {
                status: cachedResponse.status,
                statusText: cachedResponse.statusText,
                headers: headers
            });
        }
        
        throw error;
    }
}

// Network First with Immediate Update (for dynamic content like orders)
async function networkFirstWithImmediateUpdate(request) {
    try {
        const networkResponse = await fetch(request);
        
        if (networkResponse.ok) {
            const cache = await caches.open(DYNAMIC_CACHE);
            // Always update cache with latest data
            await cache.put(request, addCacheDate(networkResponse.clone()));
            
            // Notify clients about the update
            notifyClientsAboutUpdate(request.url);
        }
        
        return networkResponse;
    } catch (error) {
        const cachedResponse = await caches.match(request);
        
        if (cachedResponse) {
            console.log('[Service Worker] Using cached response (offline)');
            const headers = new Headers(cachedResponse.headers);
            headers.set('X-Cache-Status', 'OFFLINE');
            return new Response(cachedResponse.body, {
                status: cachedResponse.status,
                statusText: cachedResponse.statusText,
                headers: headers
            });
        }
        
        throw error;
    }
}

// Network First with Offline Page Fallback and Expiration
async function networkFirstWithOfflineAndExpiration(request) {
    try {
        const networkResponse = await fetch(request);
        
        if (networkResponse.ok) {
            const cache = await caches.open(DYNAMIC_CACHE);
            await cache.put(request, addCacheDate(networkResponse.clone()));
        }
        
        return networkResponse;
    } catch (error) {
        const cachedResponse = await caches.match(request);
        
        if (cachedResponse) {
            // Add header to indicate cached content
            const headers = new Headers(cachedResponse.headers);
            headers.set('X-Cache-Status', 'OFFLINE');
            if (isCacheExpired(cachedResponse, CACHE_EXPIRATION.dynamic)) {
                headers.set('X-Cache-Expired', 'true');
            }
            return new Response(cachedResponse.body, {
                status: cachedResponse.status,
                statusText: cachedResponse.statusText,
                headers: headers
            });
        }
        
        // Return offline page
        const offlinePage = await caches.match('/offline');
        return offlinePage || new Response('Offline - No cached version available', {
            status: 503,
            statusText: 'Service Unavailable',
            headers: new Headers({
                'Content-Type': 'text/html'
            })
        });
    }
}

// Stale While Revalidate with Expiration Check
async function staleWhileRevalidateWithExpiration(request) {
    const cachedResponse = await caches.match(request);
    
    // Always try to fetch fresh data in background
    const fetchPromise = fetch(request)
        .then((networkResponse) => {
            if (networkResponse.ok) {
                const cache = caches.open(DYNAMIC_CACHE);
                cache.then(c => {
                    c.put(request, addCacheDate(networkResponse.clone()));
                    // Notify clients about update
                    notifyClientsAboutUpdate(request.url);
                });
            }
            return networkResponse;
        })
        .catch(() => cachedResponse);
    
    return cachedResponse || fetchPromise;
}

// Utility: Limit cache size
async function limitCacheSize(cacheName, maxItems) {
    const cache = await caches.open(cacheName);
    const keys = await cache.keys();
    
    if (keys.length > maxItems) {
        // Delete oldest items
        const itemsToDelete = keys.length - maxItems;
        for (let i = 0; i < itemsToDelete; i++) {
            await cache.delete(keys[i]);
        }
    }
}

// Background sync for failed requests
self.addEventListener('sync', (event) => {
    if (event.tag === 'sync-orders') {
        event.waitUntil(syncOrders());
    }
});

async function syncOrders() {
    // Implement order sync logic here
    console.log('[Service Worker] Syncing orders...');
}

// Notify all clients about content updates
async function notifyClientsAboutUpdate(url) {
    const clients = await self.clients.matchAll({ includeUncontrolled: true, type: 'window' });
    clients.forEach(client => {
        client.postMessage({
            type: 'CONTENT_UPDATED',
            url: url,
            timestamp: Date.now()
        });
    });
}

// Invalidate dynamic cache (call when coming back online)
async function invalidateDynamicCache() {
    console.log('[Service Worker] Invalidating dynamic cache...');
    const cache = await caches.open(DYNAMIC_CACHE);
    const requests = await cache.keys();
    
    // Remove expired items
    const now = Date.now();
    for (const request of requests) {
        const response = await cache.match(request);
        if (response && isCacheExpired(response, CACHE_EXPIRATION.dynamic)) {
            await cache.delete(request);
            console.log('[Service Worker] Removed expired cache:', request.url);
        }
    }
}

// Clear all caches for specific URL patterns
async function clearCacheForPattern(pattern) {
    const cacheNames = await caches.keys();
    for (const cacheName of cacheNames) {
        const cache = await caches.open(cacheName);
        const requests = await cache.keys();
        for (const request of requests) {
            if (request.url.includes(pattern)) {
                await cache.delete(request);
                console.log('[Service Worker] Cleared cache for:', request.url);
            }
        }
    }
}

// Push notification support
self.addEventListener('push', (event) => {
    const data = event.data ? event.data.json() : {};
    const title = data.title || 'JB Shop';
    const options = {
        body: data.body || 'Nouvelle notification',
        icon: '/img/logo.png',
        badge: '/img/logo.png',
        vibrate: [200, 100, 200],
        data: data.url || '/'
    };
    
    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});

// Notification click handler
self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    
    event.waitUntil(
        clients.openWindow(event.notification.data || '/')
    );
});

// Message handler for cache updates and control
self.addEventListener('message', (event) => {
    const { data } = event;
    
    if (!data) return;
    
    // Skip waiting and activate new service worker
    if (data.type === 'SKIP_WAITING') {
        self.skipWaiting();
        return;
    }
    
    // Clear all caches
    if (data.type === 'CLEAR_CACHE') {
        event.waitUntil(
            caches.keys().then((cacheNames) => {
                return Promise.all(
                    cacheNames.map((cacheName) => caches.delete(cacheName))
                );
            }).then(() => {
                console.log('[Service Worker] All caches cleared');
                event.ports[0]?.postMessage({ success: true });
            })
        );
        return;
    }
    
    // Clear specific cache
    if (data.type === 'CLEAR_CACHE_FOR_URL') {
        event.waitUntil(
            clearCacheForPattern(data.url).then(() => {
                console.log('[Service Worker] Cache cleared for:', data.url);
                event.ports[0]?.postMessage({ success: true });
            })
        );
        return;
    }
    
    // Force update specific URL
    if (data.type === 'FORCE_UPDATE') {
        event.waitUntil(
            fetch(data.url).then(response => {
                if (response.ok) {
                    return caches.open(DYNAMIC_CACHE).then(cache => {
                        return cache.put(data.url, addCacheDate(response));
                    });
                }
            }).then(() => {
                console.log('[Service Worker] Force updated:', data.url);
                event.ports[0]?.postMessage({ success: true });
            }).catch(error => {
                console.error('[Service Worker] Force update failed:', error);
                event.ports[0]?.postMessage({ success: false, error: error.message });
            })
        );
        return;
    }
    
    // Check online status
    if (data.type === 'CHECK_ONLINE') {
        event.ports[0]?.postMessage({ isOnline: isOnline });
        return;
    }
});

console.log('[Service Worker] Loaded successfully');
