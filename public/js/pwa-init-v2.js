/**
 * PWA Initialization Script - Version 2.4.0
 * JB Shop - Full PWA Support for Mobile and Desktop
 * 
 * This script handles:
 * - Service Worker registration
 * - Install prompt (beforeinstallprompt)
 * - Mobile install modal
 * - Floating install button
 * - iOS manual install instructions
 */

(function() {
    'use strict';
    
    const PWA_VERSION = '2.4.0';
    let deferredPrompt = null;
    let modalDisplayed = false;
    let buttonDisplayed = false;
    
    console.log(`[PWA v${PWA_VERSION}] Script loaded`);
    
    // ==========================================
    // UTILITY FUNCTIONS
    // ==========================================
    
    function isMobile() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }
    
    function isIOS() {
        return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
    }
    
    function isAndroid() {
        return /Android/i.test(navigator.userAgent);
    }
    
    function isStandalone() {
        return window.matchMedia('(display-mode: standalone)').matches || 
               window.navigator.standalone === true;
    }
    
    function isInstalled() {
        return isStandalone() || localStorage.getItem('pwa_installed') === 'true';
    }
    
    function isSecure() {
        return location.protocol === 'https:' || location.hostname === 'localhost' || location.hostname === '127.0.0.1';
    }
    
    // ==========================================
    // SERVICE WORKER REGISTRATION
    // ==========================================
    
    async function registerServiceWorker() {
        if (!('serviceWorker' in navigator)) {
            console.log('[PWA] Service Workers not supported');
            return false;
        }
        
        if (!isSecure()) {
            console.warn('[PWA] ⚠️ HTTPS required for PWA! Current:', location.protocol);
            return false;
        }
        
        try {
            // Clear old caches first
            if ('caches' in window) {
                const cacheNames = await caches.keys();
                for (const name of cacheNames) {
                    if (name.includes('jbshop-v2.0') || name.includes('jbshop-v2.1') || name.includes('jbshop-v2.2')) {
                        await caches.delete(name);
                        console.log('[PWA] Deleted old cache:', name);
                    }
                }
            }
            
            const registration = await navigator.serviceWorker.register('/service-worker.js', {
                scope: '/',
                updateViaCache: 'none'
            });
            
            console.log('[PWA] ✅ Service Worker registered:', registration.scope);
            
            // Wait for active service worker
            if (registration.active) {
                console.log('[PWA] ✅ Service Worker active');
            } else {
                console.log('[PWA] Service Worker installing...');
            }
            
            // Check for updates periodically
            setInterval(() => {
                registration.update();
            }, 60 * 60 * 1000); // Every hour
            
            return true;
        } catch (error) {
            console.error('[PWA] ❌ Service Worker registration failed:', error);
            return false;
        }
    }
    
    // ==========================================
    // INSTALL PROMPT HANDLING
    // ==========================================
    
    function setupInstallPrompt() {
        // Listen for install prompt
        window.addEventListener('beforeinstallprompt', (e) => {
            console.log('[PWA] ✅ beforeinstallprompt event received!');
            
            // Prevent Chrome's mini-infobar
            e.preventDefault();
            
            // Save the event for later
            deferredPrompt = e;
            
            // Show our custom UI
            showInstallButton();
            
            // Show modal on mobile after delay
            if (isMobile()) {
                setTimeout(() => {
                    showInstallModal();
                }, 2500);
            }
        });
        
        // Listen for successful installation
        window.addEventListener('appinstalled', () => {
            console.log('[PWA] ✅ App installed successfully!');
            localStorage.setItem('pwa_installed', 'true');
            deferredPrompt = null;
            hideInstallButton();
            hideInstallModal();
        });
        
        // For browsers that don't fire beforeinstallprompt (iOS Safari)
        if (isIOS() && !isInstalled()) {
            console.log('[PWA] iOS detected - showing manual instructions');
            setTimeout(() => {
                showIOSInstructions();
            }, 3000);
        }
        
        // Log diagnostic info after a delay
        setTimeout(() => {
            logDiagnostics();
        }, 4000);
    }
    
    function logDiagnostics() {
        console.log('[PWA] === DIAGNOSTICS ===');
        console.log('[PWA] Protocol:', location.protocol);
        console.log('[PWA] Secure:', isSecure());
        console.log('[PWA] Mobile:', isMobile());
        console.log('[PWA] iOS:', isIOS());
        console.log('[PWA] Android:', isAndroid());
        console.log('[PWA] Standalone:', isStandalone());
        console.log('[PWA] Installed:', isInstalled());
        console.log('[PWA] Install prompt available:', !!deferredPrompt);
        console.log('[PWA] ===================');
        
        if (!deferredPrompt && !isInstalled() && !isIOS()) {
            console.log('[PWA] ⚠️ Install prompt not available. Possible reasons:');
            console.log('   - App may already be installed');
            console.log('   - Site may not meet PWA criteria');
            console.log('   - Browser may not support PWA');
            console.log('   - HTTPS may not be properly configured');
            
            // Show manual instructions on mobile if no prompt
            if (isMobile()) {
                showManualInstructions();
            }
        }
    }
    
    // ==========================================
    // INSTALL BUTTON (Floating Action Button)
    // ==========================================
    
    function showInstallButton() {
        if (buttonDisplayed || isInstalled()) return;
        
        console.log('[PWA] 📱 Showing install button');
        
        // Check if button already exists
        let btn = document.getElementById('pwa-install-btn');
        
        if (!btn) {
            // Create floating button
            btn = document.createElement('button');
            btn.id = 'pwa-install-btn';
            btn.innerHTML = `
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
                </svg>
                <span>Installer</span>
            `;
            btn.style.cssText = `
                position: fixed;
                bottom: 80px;
                right: 20px;
                z-index: 10000;
                background: linear-gradient(135deg, #ff7e00, #ff9a00);
                color: white;
                border: none;
                border-radius: 50px;
                padding: 12px 20px;
                font-size: 14px;
                font-weight: 600;
                font-family: inherit;
                cursor: pointer;
                box-shadow: 0 4px 20px rgba(255, 126, 0, 0.4);
                display: flex;
                align-items: center;
                gap: 8px;
                transition: all 0.3s ease;
                animation: pwa-bounce 2s ease infinite;
            `;
            
            // Add animation keyframes
            if (!document.getElementById('pwa-styles')) {
                const style = document.createElement('style');
                style.id = 'pwa-styles';
                style.textContent = `
                    @keyframes pwa-bounce {
                        0%, 100% { transform: translateY(0); }
                        50% { transform: translateY(-5px); }
                    }
                    @keyframes pwa-fade-in {
                        from { opacity: 0; transform: scale(0.8); }
                        to { opacity: 1; transform: scale(1); }
                    }
                    #pwa-install-btn:hover {
                        transform: scale(1.05);
                        box-shadow: 0 6px 25px rgba(255, 126, 0, 0.5);
                    }
                `;
                document.head.appendChild(style);
            }
            
            document.body.appendChild(btn);
        }
        
        btn.style.display = 'flex';
        btn.style.animation = 'pwa-fade-in 0.3s ease, pwa-bounce 2s ease infinite 0.3s';
        
        btn.onclick = () => {
            triggerInstall();
        };
        
        buttonDisplayed = true;
    }
    
    function hideInstallButton() {
        const btn = document.getElementById('pwa-install-btn');
        if (btn) {
            btn.style.display = 'none';
        }
        buttonDisplayed = false;
    }
    
    // ==========================================
    // INSTALL MODAL (Mobile)
    // ==========================================
    
    function showInstallModal() {
        if (modalDisplayed || isInstalled()) return;
        
        // Check session storage to avoid showing multiple times per session
        if (sessionStorage.getItem('pwa_modal_shown') === 'true') {
            console.log('[PWA] Modal already shown this session');
            return;
        }
        
        // Check if user dismissed recently
        const dismissedAt = localStorage.getItem('pwa_modal_dismissed');
        if (dismissedAt) {
            const hoursSince = (Date.now() - parseInt(dismissedAt)) / (1000 * 60 * 60);
            if (hoursSince < 12) {
                console.log('[PWA] Modal dismissed recently, waiting...');
                return;
            }
        }
        
        console.log('[PWA] 🎯 Displaying install modal');
        
        // Remove existing modal if any
        const existing = document.getElementById('pwa-modal');
        if (existing) existing.remove();
        
        // Create modal
        const modal = document.createElement('div');
        modal.id = 'pwa-modal';
        modal.innerHTML = `
            <div class="pwa-modal-backdrop"></div>
            <div class="pwa-modal-dialog">
                <button class="pwa-modal-close" aria-label="Fermer">×</button>
                <div class="pwa-modal-icon">
                    <img src="/icons/icon-192x192.png" alt="JB Shop" onerror="this.src='/favicon.ico'">
                </div>
                <h2 class="pwa-modal-title">Installer JB Shop</h2>
                <p class="pwa-modal-desc">Téléchargez notre application pour une meilleure expérience d'achat !</p>
                <div class="pwa-modal-features">
                    <div class="pwa-feature">✓ Accès rapide</div>
                    <div class="pwa-feature">✓ Mode hors-ligne</div>
                    <div class="pwa-feature">✓ Notifications</div>
                </div>
                <div class="pwa-modal-buttons">
                    <button class="pwa-btn-install" id="pwa-modal-install">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/>
                        </svg>
                        Installer maintenant
                    </button>
                    <button class="pwa-btn-later" id="pwa-modal-later">Plus tard</button>
                </div>
            </div>
        `;
        
        // Add styles
        const style = document.createElement('style');
        style.textContent = `
            #pwa-modal {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                z-index: 99999;
                display: flex;
                align-items: flex-end;
                justify-content: center;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            #pwa-modal.show {
                opacity: 1;
            }
            .pwa-modal-backdrop {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
            }
            .pwa-modal-dialog {
                position: relative;
                background: white;
                border-radius: 20px 20px 0 0;
                padding: 30px 25px 40px;
                width: 100%;
                max-width: 400px;
                text-align: center;
                transform: translateY(100%);
                transition: transform 0.3s ease;
            }
            #pwa-modal.show .pwa-modal-dialog {
                transform: translateY(0);
            }
            .pwa-modal-close {
                position: absolute;
                top: 15px;
                right: 15px;
                width: 30px;
                height: 30px;
                border: none;
                background: #f0f0f0;
                border-radius: 50%;
                font-size: 20px;
                line-height: 1;
                cursor: pointer;
                color: #666;
            }
            .pwa-modal-icon {
                width: 80px;
                height: 80px;
                margin: 0 auto 20px;
                border-radius: 18px;
                overflow: hidden;
                box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            }
            .pwa-modal-icon img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .pwa-modal-title {
                margin: 0 0 10px;
                font-size: 22px;
                font-weight: 700;
                color: #333;
            }
            .pwa-modal-desc {
                margin: 0 0 20px;
                color: #666;
                font-size: 14px;
                line-height: 1.5;
            }
            .pwa-modal-features {
                display: flex;
                justify-content: center;
                gap: 15px;
                margin-bottom: 25px;
                flex-wrap: wrap;
            }
            .pwa-feature {
                font-size: 12px;
                color: #28a745;
                font-weight: 500;
            }
            .pwa-modal-buttons {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
            .pwa-btn-install {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                width: 100%;
                padding: 15px 20px;
                background: linear-gradient(135deg, #ff7e00, #ff9a00);
                color: white;
                border: none;
                border-radius: 12px;
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                transition: transform 0.2s, box-shadow 0.2s;
            }
            .pwa-btn-install:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 15px rgba(255, 126, 0, 0.4);
            }
            .pwa-btn-later {
                width: 100%;
                padding: 12px 20px;
                background: transparent;
                color: #666;
                border: 1px solid #ddd;
                border-radius: 12px;
                font-size: 14px;
                cursor: pointer;
            }
        `;
        document.head.appendChild(style);
        document.body.appendChild(modal);
        
        // Trigger animation
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                modal.classList.add('show');
            });
        });
        
        // Event listeners
        document.getElementById('pwa-modal-install').onclick = () => {
            triggerInstall();
            hideInstallModal();
        };
        
        document.getElementById('pwa-modal-later').onclick = () => {
            localStorage.setItem('pwa_modal_dismissed', Date.now().toString());
            hideInstallModal();
        };
        
        modal.querySelector('.pwa-modal-close').onclick = () => {
            hideInstallModal();
        };
        
        modal.querySelector('.pwa-modal-backdrop').onclick = () => {
            hideInstallModal();
        };
        
        sessionStorage.setItem('pwa_modal_shown', 'true');
        modalDisplayed = true;
        
        console.log('[PWA] ✅ Modal displayed successfully');
    }
    
    function hideInstallModal() {
        const modal = document.getElementById('pwa-modal');
        if (modal) {
            modal.classList.remove('show');
            setTimeout(() => modal.remove(), 300);
        }
        modalDisplayed = false;
    }
    
    // ==========================================
    // iOS INSTRUCTIONS
    // ==========================================
    
    function showIOSInstructions() {
        if (isInstalled() || sessionStorage.getItem('ios_instructions_shown') === 'true') return;
        
        console.log('[PWA] 📱 Showing iOS install instructions');
        
        const modal = document.createElement('div');
        modal.id = 'pwa-ios-modal';
        modal.innerHTML = `
            <div class="pwa-modal-backdrop"></div>
            <div class="pwa-modal-dialog">
                <button class="pwa-modal-close" aria-label="Fermer">×</button>
                <div class="pwa-modal-icon">
                    <img src="/icons/icon-192x192.png" alt="JB Shop" onerror="this.src='/favicon.ico'">
                </div>
                <h2 class="pwa-modal-title">Installer JB Shop</h2>
                <p class="pwa-modal-desc">Pour installer l'application sur votre iPhone/iPad :</p>
                <div class="pwa-ios-steps">
                    <div class="pwa-ios-step">
                        <span class="step-num">1</span>
                        <span>Appuyez sur le bouton <strong>Partager</strong> <span style="font-size: 20px;">⎘</span></span>
                    </div>
                    <div class="pwa-ios-step">
                        <span class="step-num">2</span>
                        <span>Faites défiler et sélectionnez <strong>"Sur l'écran d'accueil"</strong></span>
                    </div>
                    <div class="pwa-ios-step">
                        <span class="step-num">3</span>
                        <span>Appuyez sur <strong>"Ajouter"</strong></span>
                    </div>
                </div>
                <button class="pwa-btn-install" onclick="document.getElementById('pwa-ios-modal').remove()">J'ai compris</button>
            </div>
        `;
        
        // Add iOS-specific styles
        const style = document.createElement('style');
        style.textContent = `
            #pwa-ios-modal {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                z-index: 99999;
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            #pwa-ios-modal.show {
                opacity: 1;
            }
            .pwa-ios-steps {
                text-align: left;
                margin: 20px 0;
            }
            .pwa-ios-step {
                display: flex;
                align-items: center;
                gap: 15px;
                padding: 12px 0;
                border-bottom: 1px solid #eee;
                font-size: 14px;
            }
            .pwa-ios-step:last-child {
                border-bottom: none;
            }
            .step-num {
                width: 28px;
                height: 28px;
                background: #ff7e00;
                color: white;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                flex-shrink: 0;
            }
        `;
        document.head.appendChild(style);
        document.body.appendChild(modal);
        
        requestAnimationFrame(() => {
            modal.classList.add('show');
        });
        
        modal.querySelector('.pwa-modal-close').onclick = () => modal.remove();
        modal.querySelector('.pwa-modal-backdrop').onclick = () => modal.remove();
        
        sessionStorage.setItem('ios_instructions_shown', 'true');
    }
    
    // ==========================================
    // MANUAL INSTRUCTIONS (Fallback)
    // ==========================================
    
    function showManualInstructions() {
        if (sessionStorage.getItem('manual_instructions_shown') === 'true') return;
        
        console.log('[PWA] 📱 Showing manual install instructions');
        
        let message = '';
        if (isAndroid()) {
            message = 'Pour installer JB Shop:\n\n1. Appuyez sur le menu (⋮) en haut à droite\n2. Sélectionnez "Installer l\'application" ou "Ajouter à l\'écran d\'accueil"';
        } else {
            message = 'Pour installer JB Shop:\n\nOuvrez le menu de votre navigateur et cherchez l\'option "Installer" ou "Ajouter à l\'écran d\'accueil"';
        }
        
        // Create a toast notification
        const toast = document.createElement('div');
        toast.id = 'pwa-toast';
        toast.innerHTML = `
            <div class="pwa-toast-content">
                <img src="/icons/icon-72x72.png" alt="" style="width: 40px; height: 40px; border-radius: 8px;">
                <div>
                    <strong>Installer JB Shop</strong>
                    <p style="margin: 5px 0 0; font-size: 12px; opacity: 0.9;">
                        ${isAndroid() ? 'Menu ⋮ → Installer l\'application' : 'Utilisez le menu pour installer'}
                    </p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: white; font-size: 20px; cursor: pointer;">×</button>
            </div>
        `;
        
        const style = document.createElement('style');
        style.textContent = `
            #pwa-toast {
                position: fixed;
                bottom: 20px;
                left: 20px;
                right: 20px;
                z-index: 99999;
                animation: toast-slide-up 0.3s ease;
            }
            @keyframes toast-slide-up {
                from { transform: translateY(100%); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }
            .pwa-toast-content {
                background: linear-gradient(135deg, #ff7e00, #ff9a00);
                color: white;
                padding: 15px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                gap: 12px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            }
        `;
        document.head.appendChild(style);
        document.body.appendChild(toast);
        
        // Auto remove after 10 seconds
        setTimeout(() => {
            if (toast.parentElement) {
                toast.style.animation = 'toast-slide-up 0.3s ease reverse';
                setTimeout(() => toast.remove(), 300);
            }
        }, 10000);
        
        sessionStorage.setItem('manual_instructions_shown', 'true');
    }
    
    // ==========================================
    // TRIGGER INSTALL
    // ==========================================
    
    async function triggerInstall() {
        console.log('[PWA] 🚀 Triggering install...');
        
        if (!deferredPrompt) {
            console.log('[PWA] No deferred prompt available');
            
            // Show manual instructions
            if (isIOS()) {
                showIOSInstructions();
            } else if (isMobile()) {
                alert('Pour installer JB Shop:\n\n' + 
                      (isAndroid() 
                          ? '1. Appuyez sur le menu (⋮)\n2. Sélectionnez "Installer l\'application"' 
                          : 'Utilisez le menu de votre navigateur pour installer'));
            } else {
                alert('Pour installer JB Shop:\n\nCliquez sur l\'icône d\'installation dans la barre d\'adresse ou utilisez le menu du navigateur.');
            }
            return;
        }
        
        try {
            // Show the install prompt
            deferredPrompt.prompt();
            
            // Wait for user response
            const { outcome } = await deferredPrompt.userChoice;
            console.log(`[PWA] User choice: ${outcome}`);
            
            if (outcome === 'accepted') {
                console.log('[PWA] ✅ User accepted install');
                localStorage.setItem('pwa_installed', 'true');
            } else {
                console.log('[PWA] ❌ User dismissed install');
            }
            
            // Clear the prompt
            deferredPrompt = null;
            
        } catch (error) {
            console.error('[PWA] ❌ Install error:', error);
        }
    }
    
    // ==========================================
    // INITIALIZATION
    // ==========================================
    
    async function init() {
        console.log(`[PWA v${PWA_VERSION}] Initializing...`);
        
        // Register service worker
        await registerServiceWorker();
        
        // Setup install prompt handling
        setupInstallPrompt();
        
        // If already installed, don't show anything
        if (isInstalled()) {
            console.log('[PWA] App already installed');
            return;
        }
        
        console.log(`[PWA v${PWA_VERSION}] ✅ Initialization complete`);
    }
    
    // Start on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    // Expose for debugging
    window.PWA = {
        version: PWA_VERSION,
        showInstallModal,
        showInstallButton,
        triggerInstall,
        showIOSInstructions,
        showManualInstructions,
        isInstalled,
        isMobile,
        isIOS,
        isAndroid,
        getDeferredPrompt: () => deferredPrompt,
        clearStorage: () => {
            sessionStorage.clear();
            localStorage.removeItem('pwa_installed');
            localStorage.removeItem('pwa_modal_dismissed');
            console.log('[PWA] Storage cleared - reload to see changes');
        },
        debug: () => {
            logDiagnostics();
            console.log('[PWA] deferredPrompt:', deferredPrompt);
            console.log('[PWA] modalDisplayed:', modalDisplayed);
            console.log('[PWA] buttonDisplayed:', buttonDisplayed);
        }
    };
    
})();
