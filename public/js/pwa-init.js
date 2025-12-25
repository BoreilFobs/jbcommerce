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
            // Unregister old service workers first (force clean install)
            const registrations = await navigator.serviceWorker.getRegistrations();
            for (let registration of registrations) {
                if (registration.scope !== window.location.origin + '/') {
                    await registration.unregister();
                    console.log('[PWA] Unregistered old service worker:', registration.scope);
                }
            }
            
            const registration = await navigator.serviceWorker.register('/service-worker.js', {
                scope: '/',
                updateViaCache: 'none' // Always check for updates
            });
            
            console.log('[PWA] ✅ Service Worker registered successfully:', registration.scope);
            console.log('[PWA] Service Worker state:', registration.active ? 'active' : 'installing');
            
            // Wait for service worker to be ready
            await navigator.serviceWorker.ready;
            console.log('[PWA] ✅ Service Worker is ready!');
            
            // Check for updates
            registration.addEventListener('updatefound', () => {
                const newWorker = registration.installing;
                console.log('[PWA] Update found, installing new version...');
                
                newWorker.addEventListener('statechange', () => {
                    if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                        console.log('[PWA] New version installed!');
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
            console.error('[PWA] ❌ Service Worker registration failed:', error);
            console.error('[PWA] Error details:', error.message);
            // Still try to initialize PWA features even if SW fails
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
    let installButtonShown = false;
    
    function handleInstallPrompt() {
        // Check if already installed
        const isInstalled = window.matchMedia('(display-mode: standalone)').matches || 
                           window.navigator.standalone === true ||
                           localStorage.getItem('pwa_installed') === 'true';
        
        if (isInstalled) {
            console.log('[PWA] ✅ App is already installed!');
            return;
        }
        
        window.addEventListener('beforeinstallprompt', (e) => {
            // Prevent the mini-infobar from appearing
            e.preventDefault();
            
            // Store the event for later use
            deferredPrompt = e;
            
            console.log('[PWA] ✅ Install prompt available - App is installable!');
            console.log('[PWA] Browser:', navigator.userAgent);
            console.log('[PWA] Protocol:', window.location.protocol);
            
            // Show floating install button immediately
            showInstallButton();
            
            // Show custom install modal (mobile and desktop)
            showInstallModal();
        });
        
        // Listen for successful installation
        window.addEventListener('appinstalled', () => {
            console.log('[PWA] ✅ App installed successfully!');
            deferredPrompt = null;
            installButtonShown = false;
            hideInstallModal();
            hideInstallButton();
            
            // Store installation flag
            localStorage.setItem('pwa_installed', 'true');
            
            // Track installation
            if (window.gtag) {
                gtag('event', 'pwa_install', {
                    event_category: 'engagement',
                    event_label: 'PWA Installation'
                });
            }
        });
        
        // For iOS Safari - show manual instructions
        if (isiOS() && !isInstalled) {
            console.log('[PWA] iOS detected - showing iOS install instructions');
            setTimeout(() => {
                if (!deferredPrompt) {
                    showiOSInstallPrompt();
                }
            }, 3000);
        }
        
        // Log PWA readiness after 3 seconds
        setTimeout(() => {
            if (!deferredPrompt && !isInstalled) {
                console.log('[PWA] ⚠️ Install prompt not triggered. Diagnostic info:');
                console.log('- Protocol:', window.location.protocol);
                console.log('- HTTPS:', window.location.protocol === 'https:');
                console.log('- Localhost:', window.location.hostname === 'localhost');
                console.log('- Service Worker:', 'serviceWorker' in navigator);
                console.log('- BeforeInstallPrompt:', 'onbeforeinstallprompt' in window);
                console.log('- User Agent:', navigator.userAgent);
                console.log('- Display Mode:', window.matchMedia('(display-mode: standalone)').matches ? 'standalone' : 'browser');
                
                // Check manifest
                fetch('/manifest.json')
                    .then(r => r.ok ? console.log('[PWA] ✅ Manifest.json accessible') : console.error('[PWA] ❌ Manifest.json not found'))
                    .catch(e => console.error('[PWA] ❌ Cannot fetch manifest:', e));
                    
                // If on mobile but no prompt, show manual instructions
                if (isMobileDevice()) {
                    console.log('[PWA] Mobile device detected, showing fallback instructions');
                    showManualInstallInstructions();
                }
            }
        }, 3000);
    }
    
    // Show floating install button
    function showInstallButton() {
        const installBtn = document.getElementById('pwa-install-button');
        if (!installBtn) {
            console.error('[PWA] ❌ Install button element not found in DOM');
            return;
        }
        
        if (deferredPrompt && !installButtonShown) {
            // Check if already installed
            const isInstalled = window.matchMedia('(display-mode: standalone)').matches || 
                               window.navigator.standalone === true ||
                               localStorage.getItem('pwa_installed') === 'true';
                               
            if (isInstalled) {
                console.log('[PWA] App already installed, hiding button');
                return;
            }
            
            console.log('[PWA] 📱 Showing install button');
            installBtn.style.display = 'flex';
            installBtn.style.visibility = 'visible';
            installBtn.style.opacity = '1';
            
            // Remove old listeners to prevent duplicates
            const newBtn = installBtn.cloneNode(true);
            installBtn.parentNode.replaceChild(newBtn, installBtn);
            newBtn.addEventListener('click', installPWA);
            
            installButtonShown = true;
        } else if (!deferredPrompt) {
            console.log('[PWA] ⚠️ Install button not shown - prompt not available yet');
        }
    }
    
    // Hide floating install button
    function hideInstallButton() {
        const installBtn = document.getElementById('pwa-install-button');
        if (installBtn) {
            installBtn.style.display = 'none';
        }
    }
    
    // Check if device is mobile
    function isMobileDevice() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }
    
    // Check if running on iOS
    function isiOS() {
        return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
    }
    
    // Show iOS install prompt
    function showiOSInstallPrompt() {
        const modal = document.getElementById('pwa-install-modal');
        if (modal) {
            const content = modal.querySelector('.modal-body') || modal.querySelector('.pwa-modal-content');
            if (content) {
                content.innerHTML = `
                    <div style="text-align: center; padding: 20px;">
                        <h3 style="margin-bottom: 15px;">Installer JB Shop</h3>
                        <p style="margin-bottom: 15px;">Sur iOS, appuyez sur le bouton Partager <span style="font-size: 24px;">⎘</span> puis "Sur l'écran d'accueil"</p>
                        <img src="/icons/icon-192x192.png" alt="JB Shop" style="width: 80px; height: 80px; margin: 20px auto; display: block; border-radius: 16px;">
                        <button onclick="this.closest('.pwa-modal').style.display='none'" style="margin-top: 20px; padding: 10px 30px; background: #ff7e00; color: white; border: none; border-radius: 8px; cursor: pointer;">OK</button>
                    </div>
                `;
                modal.style.display = 'flex';
                console.log('[PWA] Showing iOS install instructions');
            }
        }
    }
    
    // Show manual install instructions
    function showManualInstallInstructions() {
        const modal = document.getElementById('pwa-install-modal');
        if (modal) {
            const content = modal.querySelector('.modal-body') || modal.querySelector('.pwa-modal-content');
            if (content) {
                const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
                const isAndroid = /Android/.test(navigator.userAgent);
                
                let instructions = '<div style="text-align: center; padding: 20px;"><h3 style="margin-bottom: 15px;">Installer JB Shop</h3>';
                
                if (isIOS) {
                    instructions += '<p style="margin-bottom: 15px;">1. Appuyez sur le bouton Partager <span style="font-size: 24px;">⎘</span></p>';
                    instructions += '<p style="margin-bottom: 15px;">2. Faites défiler et sélectionnez "Sur l\'écran d\'accueil"</p>';
                    instructions += '<p style="margin-bottom: 15px;">3. Appuyez sur "Ajouter"</p>';
                } else if (isAndroid) {
                    instructions += '<p style="margin-bottom: 15px;">1. Appuyez sur le menu (⋮) en haut à droite</p>';
                    instructions += '<p style="margin-bottom: 15px;">2. Sélectionnez "Installer l\'application" ou "Ajouter à l\'écran d\'accueil"</p>';
                } else {
                    instructions += '<p style="margin-bottom: 15px;">Dans votre navigateur, cherchez l\'option "Installer" dans le menu</p>';
                }
                
                instructions += '<img src="/icons/icon-192x192.png" alt="JB Shop" style="width: 80px; height: 80px; margin: 20px auto; display: block; border-radius: 16px;">';
                instructions += '<button onclick="this.closest(\'.pwa-modal\').style.display=\'none\'" style="margin-top: 20px; padding: 10px 30px; background: #ff7e00; color: white; border: none; border-radius: 8px; cursor: pointer;">OK</button></div>';
                
                content.innerHTML = instructions;
                modal.style.display = 'flex';
                console.log('[PWA] Showing manual install instructions');
            }
        }
    }
    
    // Show Install Modal
    function showInstallModal() {
        // Always check if prompt is available
        if (!deferredPrompt) {
            console.log('[PWA] Install prompt not available yet');
            return;
        }
        
        // Check if already installed
        const isInstalled = window.matchMedia('(display-mode: standalone)').matches || 
                           window.navigator.standalone === true ||
                           localStorage.getItem('pwa_installed') === 'true';
        if (isInstalled) {
            console.log('[PWA] App already installed');
            return;
        }
        
        // Show on mobile devices primarily, but allow desktop too
        if (!isMobileDevice()) {
            console.log('[PWA] Desktop detected - install button will be shown instead of modal');
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
        
        // Delay modal display by 3 seconds for better UX
        setTimeout(() => {
            displayInstallModal();
        }, 3000);
    }
    
    // Display the install modal
    function displayInstallModal() {
        // Create modal overlay
        const modalOverlay = document.createElement('div');
        modalOverlay.id = 'pwa-install-modal';
        modalOverlay.className = 'pwa-modal-overlay';
        modalOverlay.innerHTML = `
            <div class="pwa-modal-content">
                <div class="pwa-modal-header">
                    <button class="pwa-modal-close" id="pwa-modal-close">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="pwa-modal-icon">
                        <i class="fab fa-android"></i>
                    </div>
                    <h3 class="pwa-modal-title">Installer JB Shop</h3>
                </div>
                <div class="pwa-modal-body">
                    <p class="pwa-modal-description">
                        Téléchargez notre application mobile pour une meilleure expérience d'achat !
                    </p>
                </div>
                <div class="pwa-modal-footer">
                    <button id="pwa-install-now" class="btn-pwa-install">
                        <i class="fas fa-download me-2"></i>Télécharger
                    </button>
                    <a href="https://wa.me/237682252932" target="_blank" class="btn-pwa-whatsapp">
                        <i class="fab fa-whatsapp me-2"></i>WhatsApp
                    </a>
                    <button id="pwa-remind-later" class="btn-pwa-later">
                        Plus tard
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
        padding: 40px 20px 25px;
        text-align: center;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }
    
    .pwa-modal-icon {
        width: 100px;
        height: 100px;
        background: white;
        border-radius: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        animation: float 3s ease-in-out infinite;
    }
    
    .pwa-modal-icon i {
        font-size: 60px;
        color: #3ddc84;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
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
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-pwa-install:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 126, 0, 0.4);
    }
    
    .btn-pwa-install:active {
        transform: translateY(0);
    }
    
    .btn-pwa-whatsapp {
        background: #25D366;
        color: white;
        text-decoration: none;
        padding: 13px 25px;
        border-radius: 50px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
    }
    
    .btn-pwa-whatsapp:hover {
        background: #20BA5A;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
        color: white;
    }
    
    .btn-pwa-whatsapp:active {
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
            padding: 30px 15px 20px;
        }
        
        .pwa-modal-icon {
            width: 80px;
            height: 80px;
        }
        
        .pwa-modal-icon i {
            font-size: 45px;
        }
        
        .pwa-modal-title {
            font-size: 20px;
        }
        
        .btn-pwa-install, .btn-pwa-whatsapp {
            font-size: 14px;
            padding: 12px 20px;
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
