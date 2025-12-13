// Service Worker for JB Shop - PWA with Offline Support
// Version 1.0.0

const CACHE_VERSION = 'jbshop-v1.0.0';
const STATIC_CACHE = `${CACHE_VERSION}-static`;
const DYNAMIC_CACHE = `${CACHE_VERSION}-dynamic`;
const IMAGE_CACHE = `${CACHE_VERSION}-images`;

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

// API endpoints that need network-first strategy
const API_ENDPOINTS = [
    '/api/',
    '/cart/',
    '/checkout/',
    '/orders/'
];

// Maximum cache sizes
const MAX_DYNAMIC_CACHE_SIZE = 50;
const MAX_IMAGE_CACHE_SIZE = 100;

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

// Fetch event - implement caching strategies
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
    
    // Handle different types of requests with appropriate strategies
    
    // 1. Core static assets - Cache First (shell architecture)
    if (CORE_ASSETS.some(asset => url.pathname === asset || url.pathname.startsWith(asset))) {
        event.respondWith(cacheFirst(request, STATIC_CACHE));
        return;
    }
    
    // 2. Images - Cache First with fallback
    if (request.destination === 'image' || url.pathname.match(/\.(jpg|jpeg|png|gif|svg|webp|ico)$/i)) {
        event.respondWith(cacheFirstImages(request));
        return;
    }
    
    // 3. API calls and dynamic content - Network First
    if (API_ENDPOINTS.some(endpoint => url.pathname.startsWith(endpoint))) {
        event.respondWith(networkFirst(request));
        return;
    }
    
    // 4. HTML pages - Network First with offline fallback
    if (request.headers.get('accept')?.includes('text/html')) {
        event.respondWith(networkFirstWithOffline(request));
        return;
    }
    
    // 5. Everything else - Stale While Revalidate
    event.respondWith(staleWhileRevalidate(request));
});

// Cache First Strategy (for static assets)
async function cacheFirst(request, cacheName = STATIC_CACHE) {
    const cachedResponse = await caches.match(request);
    
    if (cachedResponse) {
        return cachedResponse;
    }
    
    try {
        const networkResponse = await fetch(request);
        
        if (networkResponse.ok) {
            const cache = await caches.open(cacheName);
            cache.put(request, networkResponse.clone());
        }
        
        return networkResponse;
    } catch (error) {
        console.error('[Service Worker] Cache First failed:', error);
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

// Network First Strategy (for dynamic content)
async function networkFirst(request) {
    try {
        const networkResponse = await fetch(request);
        
        if (networkResponse.ok) {
            const cache = await caches.open(DYNAMIC_CACHE);
            cache.put(request, networkResponse.clone());
            
            // Limit cache size
            limitCacheSize(DYNAMIC_CACHE, MAX_DYNAMIC_CACHE_SIZE);
        }
        
        return networkResponse;
    } catch (error) {
        const cachedResponse = await caches.match(request);
        
        if (cachedResponse) {
            return cachedResponse;
        }
        
        throw error;
    }
}

// Network First with Offline Page Fallback
async function networkFirstWithOffline(request) {
    try {
        const networkResponse = await fetch(request);
        
        if (networkResponse.ok) {
            const cache = await caches.open(DYNAMIC_CACHE);
            cache.put(request, networkResponse.clone());
        }
        
        return networkResponse;
    } catch (error) {
        const cachedResponse = await caches.match(request);
        
        if (cachedResponse) {
            return cachedResponse;
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

// Stale While Revalidate Strategy
async function staleWhileRevalidate(request) {
    const cachedResponse = await caches.match(request);
    
    const fetchPromise = fetch(request)
        .then((networkResponse) => {
            if (networkResponse.ok) {
                const cache = caches.open(DYNAMIC_CACHE);
                cache.then(c => c.put(request, networkResponse.clone()));
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

// Message handler for cache updates
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
    
    if (event.data && event.data.type === 'CLEAR_CACHE') {
        event.waitUntil(
            caches.keys().then((cacheNames) => {
                return Promise.all(
                    cacheNames.map((cacheName) => caches.delete(cacheName))
                );
            })
        );
    }
});

console.log('[Service Worker] Loaded successfully');
