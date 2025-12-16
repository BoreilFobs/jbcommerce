// Service Worker Registration and PWA Initialization
// Version 1.0.0

(function() {
    'use strict';
    
    // Detect if running in WebView/Hybrid app
    function isWebView() {
        const ua = navigator.userAgent || navigator.vendor || window.opera;
        return (
            window.ReactNativeWebView !== undefined || // React Native
            /wv/.test(ua) || // WebView identifier
            /Android.*AppleWebKit(?!.*Safari)/i.test(ua) || // Android WebView
            /(iPhone|iPod|iPad).*AppleWebKit(?!.*Safari)/i.test(ua) // iOS WebView
        );
    }
    
    // Check if service workers are supported
    if ('serviceWorker' in navigator) {
        // Wait for the page to load
        window.addEventListener('load', () => {
            registerServiceWorker();
            initPWAFeatures();
        });
    } else {
        console.log('[PWA] Service Workers not supported in this browser');
    }
    
    // Listen for messages from service worker
    if (navigator.serviceWorker) {
        navigator.serviceWorker.addEventListener('message', handleServiceWorkerMessage);
    }
    
    // Handle messages from service worker
    function handleServiceWorkerMessage(event) {
        const { data } = event;
        
        if (!data) return;
        
        // Content updated - show notification or reload
        if (data.type === 'CONTENT_UPDATED') {
            console.log('[PWA] Content updated:', data.url);
            
            // Check if current page was updated
            if (window.location.pathname === new URL(data.url).pathname) {
                showContentUpdateBanner();
            }
        }
    }
    
    // Show banner when content is updated (DISABLED - bannière désactivée)
    function showContentUpdateBanner() {
        // Bannière complètement désactivée pour ne pas embêter les utilisateurs
        console.log('[PWA] Content update banner disabled');
        return;
    }
    
    // Register Service Worker
    async function registerServiceWorker() {
        try {
            const registration = await navigator.serviceWorker.register('/service-worker.js', {
                scope: '/'
            });
            
            console.log('[PWA] Service Worker registered successfully:', registration.scope);
            
            // Check for updates
            registration.addEventListener('updatefound', () => {
                const newWorker = registration.installing;
                
                newWorker.addEventListener('statechange', () => {
                    if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                        // New service worker available
                        showUpdateNotification();
                    }
                });
            });
            
            // Periodic update check (every 30 minutes for dynamic content)
            setInterval(() => {
                registration.update();
            }, 1800000);
            
            // Check for cache freshness on page load
            checkCacheFreshness();
            
        } catch (error) {
            console.error('[PWA] Service Worker registration failed:', error);
        }
    }
    
    // Check if cached content is fresh
    async function checkCacheFreshness() {
        const response = await fetch(window.location.href);
        const cacheStatus = response.headers.get('X-Cache-Status');
        
        if (cacheStatus === 'OFFLINE' || cacheStatus === 'EXPIRED') {
            console.log('[PWA] Displaying cached content (offline or expired)');
            showOfflineBanner();
        }
    }
    
    // Show offline banner (DISABLED - bannière désactivée)
    function showOfflineBanner() {
        // Bannière complètement désactivée pour ne pas embêter les utilisateurs
        console.log('[PWA] Offline banner disabled');
        return;
    }
    
    // Initialize PWA Features
    function initPWAFeatures() {
        // Handle install prompt
        handleInstallPrompt();
        
        // Initialize connection monitoring
        monitorConnection();
        
        // Cache important resources
        cacheImportantResources();
        
        // Setup background sync
        setupBackgroundSync();
    }
    
    // Handle PWA Install Prompt
    let deferredPrompt;
    
    function handleInstallPrompt() {
        window.addEventListener('beforeinstallprompt', (e) => {
            // Prevent the mini-infobar from appearing
            e.preventDefault();
            
            // Store the event for later use
            deferredPrompt = e;
            
            // Show custom install button/banner
            showInstallBanner();
        });
        
        // Listen for successful installation
        window.addEventListener('appinstalled', () => {
            console.log('[PWA] App installed successfully');
            deferredPrompt = null;
            hideInstallBanner();
            
            // Track installation
            if (window.gtag) {
                gtag('event', 'pwa_install', {
                    event_category: 'engagement',
                    event_label: 'PWA Installation'
                });
            }
        });
    }
    
    // Show Install Banner
    function showInstallBanner() {
        // Check if banner was dismissed recently
        const dismissedAt = localStorage.getItem('pwa_install_dismissed');
        if (dismissedAt) {
            const daysSinceDismissal = (Date.now() - parseInt(dismissedAt)) / (1000 * 60 * 60 * 24);
            if (daysSinceDismissal < 7) {
                return; // Don't show for 7 days after dismissal
            }
        }
        
        const banner = document.createElement('div');
        banner.id = 'pwa-install-banner';
        banner.className = 'pwa-install-banner';
        banner.innerHTML = `
            <div class="container">
                <div class="row align-items-center py-3">
                    <div class="col-auto">
                        <img src="/img/logo.png" alt="JB Shop" style="width: 40px; height: 40px;">
                    </div>
                    <div class="col">
                        <strong>Installer JB Shop</strong>
                        <p class="mb-0 small">Accès rapide et mode hors ligne</p>
                    </div>
                    <div class="col-auto">
                        <button id="pwa-install-btn" class="btn btn-primary btn-sm me-2">
                            <i class="fas fa-download me-1"></i>Installer
                        </button>
                        <button id="pwa-dismiss-btn" class="btn btn-outline-secondary btn-sm">
                            ×
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(banner);
        
        // Add event listeners
        document.getElementById('pwa-install-btn')?.addEventListener('click', installPWA);
        document.getElementById('pwa-dismiss-btn')?.addEventListener('click', dismissInstallBanner);
        
        // Show banner with animation
        setTimeout(() => {
            banner.style.transform = 'translateY(0)';
        }, 100);
    }
    
    // Install PWA
    async function installPWA() {
        if (!deferredPrompt) return;
        
        // Show the install prompt
        deferredPrompt.prompt();
        
        // Wait for user response
        const { outcome } = await deferredPrompt.userChoice;
        
        console.log(`[PWA] User response: ${outcome}`);
        
        if (outcome === 'accepted') {
            hideInstallBanner();
        }
        
        // Clear the deferred prompt
        deferredPrompt = null;
    }
    
    // Dismiss Install Banner
    function dismissInstallBanner() {
        const banner = document.getElementById('pwa-install-banner');
        if (banner) {
            banner.style.transform = 'translateY(-100%)';
            setTimeout(() => banner.remove(), 300);
        }
        
        // Remember dismissal
        localStorage.setItem('pwa_install_dismissed', Date.now().toString());
    }
    
    // Hide Install Banner
    function hideInstallBanner() {
        const banner = document.getElementById('pwa-install-banner');
        if (banner) {
            banner.style.transform = 'translateY(-100%)';
            setTimeout(() => banner.remove(), 300);
        }
    }
    
    // Monitor Connection
    function monitorConnection() {
        // Update online/offline status
        function updateOnlineStatus() {
            if (navigator.onLine) {
                console.log('[PWA] Connection restored');
                document.body.classList.remove('offline');
                document.body.classList.add('online');
            } else {
                console.log('[PWA] Connection lost');
                document.body.classList.remove('online');
                document.body.classList.add('offline');
            }
        }
        
        window.addEventListener('online', updateOnlineStatus);
        window.addEventListener('offline', updateOnlineStatus);
        
        // Initial check
        updateOnlineStatus();
    }
    
    // Cache Important Resources
    async function cacheImportantResources() {
        if ('caches' in window) {
            try {
                const cache = await caches.open('jbshop-v1.0.0-important');
                
                const resourcesToCache = [
                    '/',
                    '/store',
                    '/offline'
                ];
                
                // Cache resources silently in the background
                cache.addAll(resourcesToCache).catch(err => {
                    console.warn('[PWA] Some resources could not be cached:', err);
                });
                
            } catch (error) {
                console.warn('[PWA] Cache API not available:', error);
            }
        }
    }
    
    // Setup Background Sync
    function setupBackgroundSync() {
        if ('sync' in navigator.serviceWorker.registration) {
            // Register sync event for failed requests
            navigator.serviceWorker.ready.then(registration => {
                return registration.sync.register('sync-orders');
            }).catch(err => {
                console.warn('[PWA] Background sync not available:', err);
            });
        }
    }
    
    // Update Service Worker
    function updateServiceWorker() {
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.getRegistration().then(registration => {
                if (registration) {
                    registration.update();
                }
            });
        }
    }
    
    // Show Update Notification (DISABLED - bannière désactivée)
    function showUpdateNotification() {
        // Bannière complètement désactivée pour ne pas embêter les utilisateurs
        console.log('[PWA] Update notification banner disabled');
        return;
    }
    
    // Expose global PWA utilities
    window.PWA = {
        install: installPWA,
        update: updateServiceWorker,
        isOnline: () => navigator.onLine,
        isInstalled: () => window.matchMedia('(display-mode: standalone)').matches
    };
    
})();

// Styles for PWA Components
const style = document.createElement('style');
style.textContent = `
    .pwa-install-banner,
    .update-notification {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 10000;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transform: translateY(-100%);
        transition: transform 0.3s ease-out;
    }
    
    .update-notification {
        background: linear-gradient(135deg, #f28b00 0%, #d67700 100%);
    }
    
    .pwa-install-banner p,
    .update-notification p {
        color: rgba(255,255,255,0.9);
    }
    
    .pwa-install-banner .btn,
    .update-notification .btn {
        border-radius: 20px;
        font-weight: 600;
    }
    
    /* Offline mode indicator */
    body.offline::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: #dc3545;
        z-index: 99999;
    }
    
    /* Content update banner */
    .content-update-banner {
        position: fixed;
        top: 70px;
        right: 20px;
        z-index: 9999;
        animation: slideInRight 0.3s ease-out;
    }
    
    .content-update-banner-inner {
        background: #28a745;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .content-update-banner-inner i {
        animation: spin 2s linear infinite;
    }
    
    .content-update-banner button {
        border: none;
        cursor: pointer;
    }
    
    /* Offline banner */
    .offline-banner {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 9999;
        background: #ff9800;
        color: white;
        padding: 10px;
        text-align: center;
        animation: slideInDown 0.3s ease-out;
    }
    
    .offline-banner-inner {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideInDown {
        from {
            transform: translateY(-100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
`;
document.head.appendChild(style);

// Expose PWA utilities globally
window.PWA = {
    // Reload page with fresh content
    reloadPage: function() {
        if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
            // Clear cache for current URL
            navigator.serviceWorker.controller.postMessage({
                type: 'CLEAR_CACHE_FOR_URL',
                url: window.location.href
            });
        }
        setTimeout(() => {
            window.location.reload(true);
        }, 300);
    },
    
    // Force update from server
    forceUpdate: async function(url = window.location.href) {
        if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
            return new Promise((resolve) => {
                const messageChannel = new MessageChannel();
                messageChannel.port1.onmessage = (event) => {
                    resolve(event.data.success);
                };
                navigator.serviceWorker.controller.postMessage({
                    type: 'FORCE_UPDATE',
                    url: url
                }, [messageChannel.port2]);
            });
        }
        return false;
    },
    
    // Clear all caches
    clearAllCaches: async function() {
        if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
            return new Promise((resolve) => {
                const messageChannel = new MessageChannel();
                messageChannel.port1.onmessage = (event) => {
                    if (event.data.success) {
                        console.log('[PWA] All caches cleared');
                    }
                    resolve(event.data.success);
                };
                navigator.serviceWorker.controller.postMessage({
                    type: 'CLEAR_CACHE'
                }, [messageChannel.port2]);
            });
        }
        return false;
    },
    
    // Check if online
    isOnline: function() {
        return navigator.onLine;
    },
    
    // Update service worker
    updateServiceWorker: async function() {
        if ('serviceWorker' in navigator) {
            const registration = await navigator.serviceWorker.getRegistration();
            if (registration) {
                await registration.update();
                console.log('[PWA] Service worker update check initiated');
            }
        }
    }
};

console.log('[PWA] Initialization script loaded');
