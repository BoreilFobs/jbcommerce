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
            
            // Show custom install modal (only on mobile)
            showInstallModal();
        });
        
        // Listen for successful installation
        window.addEventListener('appinstalled', () => {
            console.log('[PWA] App installed successfully');
            deferredPrompt = null;
            hideInstallModal();
            
            // Track installation
            if (window.gtag) {
                gtag('event', 'pwa_install', {
                    event_category: 'engagement',
                    event_label: 'PWA Installation'
                });
            }
        });
    }
    
    // Check if device is mobile
    function isMobileDevice() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }
    
    // Show Install Modal (Mobile Only)
    function showInstallModal() {
        // Only show on mobile devices
        if (!isMobileDevice()) {
            console.log('[PWA] Install modal disabled on desktop');
            return;
        }
        
        // Check if already installed
        if (window.matchMedia('(display-mode: standalone)').matches) {
            console.log('[PWA] App already installed');
            return;
        }
        
        // Check if modal was shown before
        const modalShown = localStorage.getItem('pwa_install_modal_shown');
        if (modalShown === 'true') {
            console.log('[PWA] Install modal already shown');
            return;
        }
        
        // Check if user dismissed with "Remind me later"
        const remindLater = localStorage.getItem('pwa_install_remind_later');
        if (remindLater) {
            const daysSinceReminder = (Date.now() - parseInt(remindLater)) / (1000 * 60 * 60 * 24);
            if (daysSinceReminder < 3) {
                console.log('[PWA] User chose remind me later, waiting 3 days');
                return;
            }
        }
        
        // Create modal overlay
        const modalOverlay = document.createElement('div');
        modalOverlay.id = 'pwa-install-modal';
        modalOverlay.className = 'pwa-modal-overlay';
        modalOverlay.innerHTML = `
            <div class="pwa-modal-content">
                <div class="pwa-modal-header">
                    <div class="pwa-modal-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="pwa-modal-title">Installer JB Shop</h3>
                    <button class="pwa-modal-close" id="pwa-modal-close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="pwa-modal-body">
                    <div class="pwa-feature">
                        <i class="fas fa-bolt text-warning"></i>
                        <span>Accès ultra-rapide</span>
                    </div>
                    <div class="pwa-feature">
                        <i class="fas fa-wifi-slash text-info"></i>
                        <span>Fonctionne hors ligne</span>
                    </div>
                    <div class="pwa-feature">
                        <i class="fas fa-bell text-danger"></i>
                        <span>Notifications de commandes</span>
                    </div>
                    <div class="pwa-feature">
                        <i class="fas fa-home text-success"></i>
                        <span>Comme une vraie application</span>
                    </div>
                    <p class="pwa-modal-description">
                        Installez JB Shop sur votre écran d'accueil pour une expérience optimale !
                    </p>
                </div>
                <div class="pwa-modal-footer">
                    <button id="pwa-install-now" class="btn-pwa-install">
                        <i class="fas fa-download me-2"></i>Installer Maintenant
                    </button>
                    <button id="pwa-remind-later" class="btn-pwa-later">
                        <i class="far fa-clock me-2"></i>Me Rappeler Plus Tard
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modalOverlay);
        
        // Show modal with animation
        setTimeout(() => {
            modalOverlay.classList.add('show');
        }, 100);
        
        // Add event listeners
        document.getElementById('pwa-install-now')?.addEventListener('click', installPWA);
        document.getElementById('pwa-remind-later')?.addEventListener('click', remindLater);
        document.getElementById('pwa-modal-close')?.addEventListener('click', closeInstallModal);
        
        // Close on overlay click
        modalOverlay.addEventListener('click', (e) => {
            if (e.target === modalOverlay) {
                closeInstallModal();
            }
        });
    }
    
    // Remind Later
    function remindLater() {
        localStorage.setItem('pwa_install_remind_later', Date.now().toString());
        hideInstallModal();
        console.log('[PWA] User chose remind me later');
    }
    
    // Close Install Modal
    function closeInstallModal() {
        // Mark as shown so it doesn't appear again
        localStorage.setItem('pwa_install_modal_shown', 'true');
        hideInstallModal();
    }
    
    // Install PWA
    async function installPWA() {
        if (!deferredPrompt) {
            console.log('[PWA] No install prompt available');
            return;
        }
        
        // Show the install prompt
        deferredPrompt.prompt();
        
        // Wait for user response
        const { outcome } = await deferredPrompt.userChoice;
        
        console.log(`[PWA] User response: ${outcome}`);
        
        if (outcome === 'accepted') {
            localStorage.setItem('pwa_install_modal_shown', 'true');
            hideInstallModal();
        }
        
        // Clear the deferred prompt
        deferredPrompt = null;
    }
    
    // Hide Install Modal
    function hideInstallModal() {
        const modal = document.getElementById('pwa-install-modal');
        if (modal) {
            modal.classList.remove('show');
            setTimeout(() => modal.remove(), 300);
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
    /* PWA Install Modal - Mobile Only */
    .pwa-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(5px);
        z-index: 999999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .pwa-modal-overlay.show {
        opacity: 1;
    }
    
    .pwa-modal-overlay.show .pwa-modal-content {
        transform: scale(1);
        opacity: 1;
    }
    
    .pwa-modal-content {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        max-width: 400px;
        width: 100%;
        transform: scale(0.8);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        overflow: hidden;
    }
    
    .pwa-modal-header {
        background: linear-gradient(135deg, #ff7e00 0%, #ff9933 100%);
        padding: 30px 20px 20px;
        text-align: center;
        position: relative;
    }
    
    .pwa-modal-icon {
        width: 80px;
        height: 80px;
        background: white;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .pwa-modal-icon i {
        font-size: 40px;
        color: #ff7e00;
    }
    
    .pwa-modal-title {
        color: white;
        font-size: 24px;
        font-weight: bold;
        margin: 0;
    }
    
    .pwa-modal-close {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .pwa-modal-close:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }
    
    .pwa-modal-body {
        padding: 25px 20px;
    }
    
    .pwa-feature {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 12px 0;
        font-size: 15px;
    }
    
    .pwa-feature i {
        font-size: 24px;
        width: 30px;
    }
    
    .pwa-modal-description {
        margin: 20px 0 0;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 10px;
        text-align: center;
        color: #666;
        font-size: 14px;
    }
    
    .pwa-modal-footer {
        padding: 0 20px 25px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .btn-pwa-install {
        background: linear-gradient(135deg, #ff7e00 0%, #ff9933 100%);
        color: white;
        border: none;
        padding: 15px 25px;
        border-radius: 50px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(255, 126, 0, 0.3);
    }
    
    .btn-pwa-install:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 126, 0, 0.4);
    }
    
    .btn-pwa-install:active {
        transform: translateY(0);
    }
    
    .btn-pwa-later {
        background: white;
        color: #666;
        border: 2px solid #e0e0e0;
        padding: 12px 25px;
        border-radius: 50px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-pwa-later:hover {
        border-color: #ff7e00;
        color: #ff7e00;
        background: #fff5eb;
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
    
    /* Responsive adjustments */
    @media (max-width: 576px) {
        .pwa-modal-content {
            margin: 0 10px;
        }
        
        .pwa-modal-header {
            padding: 25px 15px 15px;
        }
        
        .pwa-modal-icon {
            width: 70px;
            height: 70px;
        }
        
        .pwa-modal-icon i {
            font-size: 35px;
        }
        
        .pwa-modal-title {
            font-size: 20px;
        }
        
        .pwa-feature {
            font-size: 14px;
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
